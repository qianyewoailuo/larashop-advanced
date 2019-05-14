<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use PHPUnit\Runner\Exception;
use Exception;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
// use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Mail;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        // 从url中获取email和token参数
        $email = $request->input('email');
        $token = $request->input('token');
        // 如果有一个为空说明不是一个合法的验证链接抛出异常
        if (!$email || !$token) {
            throw new Exception('验证链接不正确');
        }
        // 从缓存中读取数据，我们把从 url 中获取的 `token` 与缓存中的值做对比
        // 如果缓存不存在或者返回的值与 url 中的 `token` 不一致就抛出异常。
        if ($token != Cache::get('email_verification_' . $email)) {
            throw new Exception('验证链接不正确或已过期');
        }
        // 根据邮箱从数据库中获取对应的用户
        // 通常来说能通过 token 校验的情况下不可能出现用户不存在
        // 但是为了代码的健壮性我们还是需要做这个判断
        if(!$user = User::where('email',$email)->first()){
            throw new Exception('用户不存在');
        }
        // 去除缓存
        Cache::forget('email_verification_'.$email);
        // 入库
        $user->update(['email_verified'=>true]);
        // 告知验证成功
        return view('pages.success',['msg'=>'邮箱验证已成功']);
    }

    // 发送邮件
    public function send(Request $request)
    {
        // $user = Auth::user();
        $user = $request->user();
        // 判断用户是否已激活
        if($user->email_verified){
            throw new Exception('您的邮箱已通过验证');
        }
        $user->notify(new EmailVerificationNotification());

        return view('pages.success',['msg'=>'验证邮件已发送']);
    }
}
