<?php

namespace App\Console\Commands\Cron;

use Illuminate\Console\Command;
use App\Models\CrowdfundingProduct;
use Carbon\Carbon;
use App\Models\Order;
use App\Services\OrderService;
use App\Jobs\RefundCrowdfundingOrders;

class FinishCrowdfunding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:finish-crowdfunding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结束众筹';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        CrowdfundingProduct::query()
            ->with(['product'])
            ->where('end_at', '<=', Carbon::now())
            ->where('status', CrowdfundingProduct::STATUS_FUNDING)
            ->get()
            ->each(function (CrowdfundingProduct $crowdfundingProduct) {
                // 如果众筹目标金额大于众筹实际金额
                if ($crowdfundingProduct->target_amount > $crowdfundingProduct->total_amount) {
                    // 调用众筹失败逻辑
                    $this->crowdfundingFailed($crowdfundingProduct);
                } else {
                    // 否则调用众筹成功逻辑
                    $this->crowdfundingSucceed($crowdfundingProduct);
                }
            });
    }

    // 众筹失败逻辑
    protected function crowdfundingFailed(CrowdfundingProduct $crowdfundingProduct)
    {
        // 先将状态改为众筹失败
        $crowdfundingProduct->update([
            'status'  => CrowdfundingProduct::STATUS_FAIL
        ]);

        // 异步任务调用退款操作
        dispatch(new RefundCrowdfundingOrders($crowdfundingProduct));

        // $orderService = app(OrderService::class);
        // // 然后查询出所有关于此众筹的订单
        // Order::query()
        //     ->where('type',Order::TYPE_CROWDFUNDING)
        //     ->whereNotNull('paid_at')
        //     ->whereHas('items',function($query) use ($crowdfundingProduct){
        //         // 当前订单商品项包含此商品
        //         $query->where('product_id',$crowdfundingProduct->product_id);
        //     })
        //     ->get()
        //     ->each(function(Order $order) use ($orderService){
        //         // 调用退款逻辑
        //         $orderService->refundOrder($order);
        //     });
    }

    // 众筹成功逻辑
    protected function crowdfundingSucceed(CrowdfundingProduct $crowdfundingProduct)
    {
        // 只需要将众筹状态改为众筹成功即可
        $crowdfundingProduct->update([
            'status' => CrowdfundingProduct::STATUS_SUCCESS
        ]);
    }
}
