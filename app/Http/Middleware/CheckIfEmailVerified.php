<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->user()->email_verified){
            // 如果是ajax请求则通过json返回
            if($request->expectsJson()){
                // 400 Bad Request 无效请求
                return response()->json(['msg'=>'请先验证邮箱'],400);
            }
            return redirect(route('email_verify_notice'));
        }
        return $next($request);
    }
}
