<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderItem;

// implements ShouldQueue 代表此监听器是异步执行的
// 默认是没有这个继承的,表示同步进行
class UpdateProductSoldCountListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        // 从事件对象中取出对应的订单
        $order = $event->getOrder();
        // 遍历订单商品
        foreach ($order->items as $item){
            // 获取关联的商品
            $product = $item->product;
            // 计算对应商品的销量
            $soldCount = OrderItem::query()
                    ->where('product_id',$product->id)
                    ->whereHas('order',function($query){
                        // 关联订单必须为已支付的
                        $query->whereNotNull('paid_at');
                    })->sum('amount');
            // 更新商品销量
            $product->update([
                'sold_count' => $soldCount,
            ]);
        }
    }
}
