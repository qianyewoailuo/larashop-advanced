<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CouponCode;
use Carbon\Carbon;
use App\Exceptions\CouponCodeUnavailableException;

class CouponCodesController extends Controller
{
    public function show($code)
    {
        // 如果优惠券不存在返回404
        // if(!$record = CouponCode::where('code',$code)->first()){
        //     // abort() 方法可以直接中断我们程序的运行，接受的参数会变成 Http 状态码返回
        //     abort(404);
        // }
        // // 如果优惠券没有启用等同于不存在也返回404
        // if(!$record->enabled){
        //     abort(404);
        // }

        // if($record->total - $record->used <=0){
        //     return response()->json(['msg'=>'该优惠券已被兑换完毕'],403);
        // }
        // if($record->not_before && $record->not_before->gt(Carbon::now())){
        //     return response()->json(['msg'=>'该优惠券现在还不能使用'],403);
        // }
        // if ($record->not_after && $record->not_after->lt(Carbon::now())) {
        //     return response()->json(['msg' => '该优惠券已过期'], 403);
        // }

        // 使用异常处理
        if (!$record = CouponCode::where('code', $code)->first()){
            throw new CouponCodeUnavailableException('优惠券不存在');
        }
        // 检查是否有异常
        $record->checkAvailable();

        return $record;
    }
}
