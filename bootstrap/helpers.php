<?php
/*
 * @Description: 辅助函数 in larashop
 * @Author: luo
 * @email: qianyewoailuo@126.com
 * @Date: 2019-05-14 09:49:18
 * @LastEditTime: 2019-05-14 11:56:44
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
