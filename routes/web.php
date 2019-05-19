<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// 默认首页
// Route::get('/','PagesController@root')->name('root');
// 将默认首页直接指向商品页面 并且访客皆可访问
Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductsController@index')->name('products.index');

Auth::routes();

Route::group(['middleware'=>'auth'],function(){
    // 邮箱验证提示路由
    Route::get('/email_verify_notice','PagesController@emailVerifyNotice')->name('email_verify_notice');
    // 邮箱验证
    Route::get('/email_verification/verify', 'EmailVerificationController@verify')->name('email_verification.verify');
    // 邮件发送
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send');

    // CheckEmailVerified 中间件开始

    Route::group(['middleware'=>'email_verified'],function(){
        // UserAddresses :
        // index
        Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
        // create
        Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
        // store
        Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
        // edit
        Route::get('user_addresses/{user_address}','UserAddressesController@edit')->name('user_addresses.edit');
        // update
        Route::put('user_addresses/{user_address}', 'UserAddressesController@update')->name('user_addresses.update');
        // delete
        Route::delete('user_addresses/{user_address}', 'UserAddressesController@destroy')->name('user_addresses.destroy');

        // Products :
        // favor
        Route::post('products/{product}/favorite','ProductsController@favor')->name('products.favor');
        // disfavor
        Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
        // favorites 收藏商品列表
        Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');

        // Cart 购物车
        // 添加入购物车
        Route::post('cart','CartController@add')->name('cart.add');
        // 购物车列表
        Route::get('cart', 'CartController@index')->name('cart.index');
        // 移除购物车商品
        Route::delete('cart/{sku}', 'CartController@remove')->name('cart.remove');

        // Order 订单路由
        // 创建订单
        Route::post('orders', 'OrdersController@store')->name('orders.store');
        // 订单列表
        Route::get('orders', 'OrdersController@index')->name('orders.index');
        // 订单详情
        Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');

    });

    // CheckEmailVerified中间件 结束

});

Route::get('products/{product}','ProductsController@show')->name('products.show');

// Alipay沙箱测试路由
// Route::get('alipay', function () {
//     return app('alipay')->web([
//         'out_trade_no' => time(),
//         'total_amount' => 1,
//         'subject'      => 'test subject - 测试',
//     ]);
// });