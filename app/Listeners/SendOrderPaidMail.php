<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrderPaidNotification;

class SendOrderPaidMail implements ShouldQueue
{
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
        // 调用 notify 方法发送邮件
        $order->user->notify(new OrderPaidNotification($order));
    }
}
