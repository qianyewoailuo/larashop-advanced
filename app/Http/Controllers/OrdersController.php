<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Models\UserAddress;
// use Carbon\Carbon;
use App\Models\Order;
// use App\Models\ProductSku;
use App\Http\Requests\OrderRequest;
// use App\Exceptions\InvalidRequestException;
// use App\Jobs\CloseOrder;
// use App\Services\CartService;
use App\Services\OrderService;
use App\Exceptions\InvalidRequestException;

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
        $remark  = $request->input('remark');
        $items   = $request->input('items');

        // 使用Service模式下 OrderService 类封装的代码进行订单提交逻辑处理
        return $orderService->store($user,$address,$remark,$items);
    }

    // 确认收货
    public function received(Order $order,Request $request)
    {
        // 检验权限
        $this->authorize('own',$order);

        // 判断订单的发货状态是否为已发货
        if($order->ship_status !== Order::SHIP_STATUS_DELIVERED){
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
