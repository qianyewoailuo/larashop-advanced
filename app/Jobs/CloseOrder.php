<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CloseOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order,$delay = 1800)
    {
        $this->order = $order;
        // 设置延迟时间,delay() 方法的参数代表多少秒之后执行
        $this->delay($delay);
    }

    /**
     * Execute the job.
     * 当前订单关闭任务类逻辑
     * 当队列处理器从队列中取出任务时,会调用 handle() 方法
     *
     * @return void
     */
    public function handle()
    {
        // 判断对应的订单是否已经被支付
        if($this->order->paid_at){
            // 如果已经支付则不需要关闭订单,直接退出
            return;
        }
        // 否则开启事务执行关闭订单逻辑
        DB::transaction(function () {
            // 将订单的 closed 字段标志为 true 即关闭订单
            $this->order->update(['closed'=>true]);
            // 循环遍历订单中的商品 SKU 将订单中的数量加回到 SKU 库存中
            foreach($this->order->items as $item){
                // 添加入库
                $item->productSku->addStock($item->amount);
            }
            // 若使用优惠券,订单关闭后恢复优惠券用量
            if($this->order->couponCode){
                $this->order->couponCode->changeUsed(false);
            }
        });
    }
}
