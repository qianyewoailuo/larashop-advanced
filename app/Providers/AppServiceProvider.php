<?php

namespace App\Providers;

use Monolog\Logger;
use Yansongda\Pay\Pay;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\CategoryTreeComposer;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 当 Laravel 渲染 products.index 和 products.show 模板时，就会使用 CategoryTreeComposer 这个来注入类目树变量
        // 同时 Laravel 还支持通配符，例如 products.* 即代表当渲染 products 目录下的模板时都执行这个 ViewComposer
        \View::composer(['products.index', 'products.show'], CategoryTreeComposer::class);
        // 除了上面的写法,还有辅助函数的写法
        // 当然考虑到5.8后不在直接支持辅助函数还是使用上面的写法比较好
        // view()->composer(['products.index', 'products.show'], 'App\Http\ViewComposers\CategoryTreeComposer');
        Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 往服务容器中注入一个名为 alipay 的单例对象
        $this->app->singleton('alipay', function () {
            $config = config('pay.alipay');
            // 前端与服务端回调
            // $config['notify_url'] = route('payment.alipay.notify');
            // ngrok_url 内网穿透
            $config['notify_url'] = ngrok_url('payment.alipay.notify');


            // loacl测试用 从 https://requestbin.fullcontact.com/ 获取
            // $config['notify_url'] = 'http://requestbin.fullcontact.com/slrhfhsl';
            $config['return_url'] = route('payment.alipay.return');
            // 判断当前项目运行环境是否为线上环境
            if (app()->environment() !== 'production') {
                $config['mode']         = 'dev';
                $config['log']['level'] = Logger::DEBUG;
            } else {
                // 正式部署线上环境时不能使用沙箱,不过测试网站暂时使用吧
                $config['mode']         = 'dev';
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个支付宝支付对象
            return Pay::alipay($config);
        });

        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            if (app()->environment() !== 'production') {
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个微信支付对象
            return Pay::wechat($config);
        });
    }
}
