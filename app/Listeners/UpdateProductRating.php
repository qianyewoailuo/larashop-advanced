<?php

namespace App\Listeners;

use App\Events\OrderReviewd;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class UpdateProductRating
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
     * @param  OrderReviewd  $event
     * @return void
     */
    public function handle(OrderReviewd $event)
    {
        // 获取items结果集
        // 通过with方法提前加载数据 避免 N+1 性能问题
        $items = $event->getOrder()->items()->with(['product'])->get();
        // 循环遍历
        foreach($items as $item){
            $result = OrderItem::query()
                    ->where('product_id',$item->product_id)
                    ->whereHas('order',function($query){
                        // 订单必须已支付
                        $query->whereNotNull('paid_at');
                    })
                    ->first([
                        DB::raw('count(*) as review_count'),
                        DB::raw('avg(rating) as rating')
                    ]);

            // 更新商品的评分和评价数
            $item->product->update([
                'rating'       => $result->rating,
                'review_count' => $result->review_count,
            ]);
        }

    }
}
