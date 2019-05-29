<?php
/*
 * @Description: 辅助函数 in larashop
 * @Author: luo
 * @email: qianyewoailuo@126.com
 * @Date: 2019-05-14 09:49:18
 * @LastEditTime: 2019-05-29 12:41:32
 */
// 测试辅助函数是否正常引入
function test_helper()
{
    return 'ok';
}
// 根据不同运行环境指定数据库配置信息
function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));

        return $db_config = [
            'connection' => 'pgsql',
            'host'  => $url['host'],
            'database' => substr($url['path'], 1),
            'username' => $url['user'],
            'password' => $url['pass'],
        ];
    }
}

// 当前请求的路由名称转换为 CSS 类名称
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

// ngrok_url 使用环境判定配置
function ngrok_url($routeName,$parameters = [])
{

    // 开发环境并且配置了 NGROK_URL
    if(app()->environment('local') && $url = config('app.ngrok_url')){
        // route() 函数的第三个参数代表是否绝对路径
        // 如果设置为 true : http://b0034789.ap.ngrok.iohttp://shop-advanced.test/products
        // 设置为 false : http://b0034789.ap.ngrok.io/products
        return $url.route($routeName,$parameters,false);
    }

    return route($routeName,$parameters);
}
