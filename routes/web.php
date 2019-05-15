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

Route::get('/','PagesController@root')->name('root');

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
        // index
        Route::get('user_addresses', 'UserAddressesController@index')->name('user_addresses.index');
        // create
        Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
        Route::post('user_addresses', 'UserAddressesController@store')->name('user_addresses.store');
    });

    // 结束

});