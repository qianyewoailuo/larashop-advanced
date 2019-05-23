<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Models\UserAddress;
use Carbon\Carbon;
use App\Models\Order;
// use App\Models\ProductSku;
use App\Http\Requests\OrderRequest;
// use App\Exceptions\InvalidRequestException;
// use App\Jobs\CloseOrder;
// use App\Services\CartService;
use App\Services\OrderService;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\SendReviewRequest;
use App\Events\OrderReviewd;
use App\Http\Requests\ApplyRefundRequest;
use App\Models\CouponCode;

class OrdersController extends Controller
{
    // 订单列表
    public function index(Request $request)
    {
        $orders = Order::query()
            // 使用 with 方法预加载，避免 N + 1 问题
            ->with(['items.product', 'items.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', compact('orders'));
    }
    // 订单详情页
    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);

        // load() 方法是延迟预加载 与预加载 with() 方法类似 都是避免 N+1 问题
        // 不同在于 load() 方法应用在已查询到的模型对象中
        // 而 with() 方法应用在 ORM 查询构造器中
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);

        /* 也可以使用常用的compact方法返回数据 */
        // 先延迟加载
        // $order = $order->load(['items.productSku', 'items.product']);
        // 再 compact()
        // return view('orders.show', compact('order'));
    }

    // 保存订单数据 - 封装最下面注释的 store 方法代码
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::query()->find($request->input('address_id'));
        $coupon = null;
        $remark  = $request->input('remark');
        $items   = $request->input('items');
        // 如果用户提交了优惠码
        if ($code = $request->input('coupon_code')) {
            $coupon = CouponCode::where('code', $code)->first();
            if (!$coupon) {
                throw new CouponCodeUnavailableException('优惠券不存在');
            }
        }

        // 使用Service模式下 OrderService 类封装的代码进行订单提交逻辑处理
        return $orderService->store($user, $address, $remark, $items, $coupon);
    }

    // 确认收货
    public function received(Order $order, Request $request)
    {
        // 检验权限
        $this->authorize('own', $order);

        // 判断订单的发货状态是否为已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
            throw new InvalidRequestException('未发货或已确认收货');
        }

        // 更新发货状态为已收到
        $order->update([
            'ship_status' => Order::SHIP_STATUS_RECEIVED,
        ]);

        // 返回原页面
        // return redirect()->back();
        // 由于从表单提交改成了 AJAX 请求 所以返回的修改
        return $order;
    }

    // 评价页面
    public function review(Order $order)
    {
        // 校验权限
        $this->authorize('own', $order);
        // 判断是否已经支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付,请支付完成后再进行评价');
        }
        // 使用 load 方法加载关联数据，避免 N + 1 性能问题
        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    // 发送评价
    public function sendReview(Order $order, SendReviewRequest $request)
    {
        // 校验权限
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        // 判断是否已经评价
        if ($order->reviewed) {
            throw new InvalidRequestException('该订单已评价，不可重复提交');
        }
        $reviews = $request->input('reviews');
        // 开启事务
        \DB::transaction(function () use ($reviews, $order) {
            // 遍历用户提交的数据
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                // 保存评分和评价
                $orderItem->update([
                    'rating'      => $review['rating'],
                    'review'      => $review['review'],
                    'reviewed_at' => Carbon::now(),
                ]);
            }
            // 将订单标记为已评价
            $order->update(['reviewed' => true]);

            // 触发评价事件更新商品评分
            event(new OrderReviewd($order));
        });

        return redirect()->back();
    }

    // 退款
    public function applyRefund(Order $order, ApplyRefundRequest $requset)
    {
        $this->authorize('own', $order);

        // 判断订单是否已付款
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单尚未支付,不能退款');
        }
        // 判断退款状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已经申请过退款,请勿重复提交');
        }
        // 将用户输入的退款理由放到订单的 extra 字段中
        $extra  = $order->extra ?: [];        // 当前extra是否有值 有使用原有的,否则使用空数组
        $extra['refund_reason'] = $requset->input('reason');
        // 将订单退款状态改为已申请退款
        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);

        // 返回
        return $order;
    }



















    // 保存订单数据
    // public function store(OrderRequest $request, CartService $cartService)
    // {
    //     // 获取当前用户实例
    //     $user = $request->user();
    //     // 在外面声明 $data 变量并放到闭包中就不报找不到变量的 warning 了
    //     $data = [];
    //     // 开始事务
    //     $order = DB::transaction(function () use ($user, $request, $cartService, $data) {
    //         // 获取当前收货地址信息的对象
    //         $address = UserAddress::query()->find($request->input('address_id'));
    //         // 更新此地址的最后使用时间
    //         // $address->update(['last_used_at'=>date('Y-m-d H:i:s')]);
    //         $address->update(['last_used_at' => Carbon::now()]);
    //         // 创建一个订单对象
    //         $order = new order([
    //             // 地址信息 将会自动转换为json格式
    //             'address' => [
    //                 'address' => $address->full_address,
    //                 'zip'   => $address->zip,
    //                 'contact_name' => $address->contact_name,
    //                 'contact_phone' => $address->contact_phone,
    //             ],
    //             // 备注信息
    //             'remark' => $request->input('remark'),
    //             // 总价格先设定为0
    //             'total_amount' => 0,
    //         ]);
    //         // 订单关联到当前用户
    //         // 更新 belongsto 从属关的关联时使用 associate()方法
    //         // 取消 belongsto 从属关的关联时使用 dissociate()方法
    //         // 其实这里的意思和 $order->user_id = $user->id是一样的
    //         // 也是将user_id更新到orde表中,但这是Laravel推荐的写法
    //         // 好处是在免于之后用到时多查询一次user表的id数据
    //         $order->user()->associate($user);
    //         // 订单保存
    //         $order->save();

    //         // 开始计算总价格
    //         $totalAmount = 0;
    //         $items = $request->input('items');
    //         // 遍历用户提交的 SKU
    //         foreach ($items as $data) {
    //             $sku = ProductSku::query()->find($data['sku_id']);
    //             // 创建 OrderItem 对象并与当前订单关联
    //             $item = $order->items()->make([
    //                 'amount' => $data['amount'],
    //                 'price' => $sku->price,
    //             ]);
    //             // 从属关联
    //             $item->product()->associate($sku->product_id);
    //             // 从属关联
    //             $item->ProductSku()->associate($sku);
    //             // 保存 orderItem
    //             $item->save();
    //             // 累计计算当前orderitem价格
    //             $totalAmount += $sku->price * $data['amount'];
    //             // 减库存并当库存不足时抛出异常
    //             if ($sku->decreaseStock($data['amount']) <= 0) {
    //                 throw new InvalidRequestException('库存不足');
    //             }
    //         }
    //         // 更新订单总金额
    //         $order->update(['total_amount' => $totalAmount]);

    //         // 将下单的商品从购物车中移除
    //         $skuIds = collect($request->input('items'))->pluck('sku_id')->all();
    //         // $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
    //         // 上述代码封装
    //         $cartService->remove($skuIds);
    //         // 将 DB::transaction() 的返回值从闭包中传递出去
    //         return $order;
    //     });
    //     // 暂时设定在 heroku 环境下不开启延迟队列任务
    //     if (!getenv('IS_IN_HEROKU')) {
    //         // 开启延迟执行队列任务
    //         $this->dispatch(new CloseOrder($order, config('app.order_ttl')));
    //     }
    //     return $order;
    // }
}
