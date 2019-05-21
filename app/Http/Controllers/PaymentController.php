<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Exceptions\InvalidRequestException;
use Carbon\Carbon;

class PaymentController extends Controller
{

    // aplipay 支付
    public function payByAlipay(Order $order,Request $request)
    {
        // 判断订单是否属于当前用户
        $this->authorize('own',$order);
        // 判断订单是否已支付或者已关闭
        if($order->paid_at || $order->closed){
            // 已支付或关闭抛出异常
            throw new InvalidRequestException('订单已支付或关闭');
        }

        // 调用支付宝的网页支付
        return app('alipay')->web([
            // 订单编号，需保证在商户端不重复
            'out_trade_no'  => $order->no,
            // 订单金额，单位元，支持小数点后两位
            'total_amount'  => $order->total_amount,
            // 订单标题
            'subject'       => '支付larashop订单:'.$order->no,
        ]);
    }
    // alipay 前端回调
    public function alipayReturn()
    {
        try{
            // 检验提交的参数是否合法
            $data = app('alipay')->verify();
            // 检测回调返回的数据有什么
            // dd($data);
        } catch(\Eception $e) {
            return view('pages.error', ['msg'=>'数据不正确']);
        }

        return view('pages.success',['msg'=>'付款成功']);

    }
    // aplipay 服务器端回调
    public function alipayNotify()
    {
        // 校验输入的参数
        $data = app('alipay')->verify();
        // 获取订单流水号 out_trader_no 是支付宝回调的参数 即商家提供的订单号
        $order = Order::query()->where('no',$data->out_trade_no)->first();
        // 判断该笔支付订单是否存在
        if(!$order){
            return 'fail';
        }
        // 如果这笔订单的状态已经是已支付
        if($order->paid_at){
            return app('alipay')->success();
        }

        $order->update([
            'paid_at' => Carbon::now(),
            'payment_method' => 'alipay',
            'payment_no' => $data->trade_no
        ]);

        return app('aplipay')->success();
        // 服务端的请求无法看到返回值不能使用dd,所以使用日志保存测试
        // \Log::debug('Alipay notify',$data->all());
    }

    // TODO 星期日星期一沙箱系统维护 先保存代码暂停测试
    // TODO file:///D:/PHP%E8%B5%84%E6%96%99/LaravelChina/L05%20Laravel%20%E6%95%99%E7%A8%8B%20-%20%E7%94%B5%E5%95%86%E5%AE%9E%E6%88%98-laravel-shop/L05%20Laravel%20%E6%95%99%E7%A8%8B%20-%20%E7%94%B5%E5%95%86%E5%AE%9E%E6%88%98-laravel-shop/1704-order-payment.html

}
