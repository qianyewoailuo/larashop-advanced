<?php
/*
 * @Description: 辅助函数 in larashop
 * @Author: luo
 * @email: qianyewoailuo@126.com
 * @Date: 2019-05-14 09:49:18
 * @LastEditTime: 2019-05-14 10:35:27
 */
// 测试辅助函数是否正常引入
function test_helper()
{
    return 'ok';
}
// 当前请求的路由名称转换为 CSS 类名称
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}