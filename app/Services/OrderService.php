<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Queue\Jobs\Job;
use App\Jobs\CloseOrder;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Exceptions\InvalidRequestException;
use App\Models\Order;
use App\Models\CouponCode;
use App\Exceptions\CouponCodeUnavailableException;

class OrderService
{
    public function store(User $user, UserAddress $address, $remark, $items,CouponCode $coupon = null)
    {
        // 如果传入了优惠券，则先检查是否可用
        if ($coupon) {
            // 但此时我们还没有计算出订单总金额，因此先不校验
            $coupon->checkAvailable();
        }

        $order = \DB::transaction(function () use ($user, $address, $remark, $items,$coupon) {
            // 更新地址最后使用时间
            $address->update(['last_used_at' => Carbon::now()]);
            // 创建订单
            $order = new Order([
                'address' => [
                    'address'       => $address->full_address,
                    'zip'           => $address->zip,
                    'contact_name'  => $address->contact_name,
                    'contact_phone' => $address->contact_phone,
                ],
                'remark'       => $remark,
                'total_amount' => 0,
            ]);
            // 订单关联用户
            $order->user()->associate($user->id);
            // 写入数据库
            $order->save();

            $totalAmount = 0;
            // 遍历用户提交的 SKU
            $data = [];
            foreach ($items as $data) {
                // 获取指定 product_sku_id 的对象
                $sku = ProductSku::find($data['sku_id']);

                // 创建一个暂不保存的 orderItem 并直接与当前订单关联
                $item = $order->items()->make([
                    'amount' => $data['amount'],
                    'price'  => $sku->price,
                ]);
                $item->product()->associate($sku->product_id);
                $item->productSku()->associate($sku->id);
                // 现在保存 orderitem
                $item->save();

                // 开始累加计算订单总价格
                $totalAmount += $sku->price * $data['amount'];

                // 判断当前库存是否能满足当前购买的商品数量
                if ($sku->decreaseStock($data['amount']) <= 0) {
                    throw new InvalidRequestException('商品库存不足');
                }
            }

            if ($coupon) {
                // 总金额已经计算出来了，检查是否符合优惠券规则
                $coupon->checkAvailable($totalAmount);
                // 把订单金额修改为优惠后的金额
                $totalAmount = $coupon->getAdjustedPrice($totalAmount);
                // 将订单与优惠券关联
                $order->couponCode()->associate($coupon);
                // 增加优惠券的用量，需判断返回值
                if ($coupon->changeUsed() <= 0) {
                    throw new CouponCodeUnavailableException('该优惠券已被兑完');
                }
            }

            // 更新订单总价格
            $order->update(['total_amount' => $totalAmount]);

            // 将下单的商品从购物车中移除
            $skuIds = collect($items)->pluck('sku_id')->all();
            // 使用辅助函数 app() 通过容器初始化 CartService 类
            app(CartService::class)->remove($skuIds);

            return $order;
        });
        // 暂时设定在 heroku 环境下不开启延迟队列任务
        if (!getenv('IS_IN_HEROKU')) {
            // 直接使用辅助函数 dispatch() 开启延迟执行队列任务
            dispatch(new CloseOrder($order, config('app.order_ttl')));
        }

        return $order;
    }
}
