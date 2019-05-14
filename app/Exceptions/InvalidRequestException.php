<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidRequestException extends Exception
{
    public function __construct(string $message = "", int $code = 400)
    {
        parent::__construct($message,$code);
    }

    public function render(Request $request)
    {
        if($request->expectsJson()){
            // 如果是ajax请求就返回json数据
            return response()->json(['msg'=>$this->message],$this->code);
        }
        // 非ajax请求返回报错页面
        return view('pages.error',['msg'=>$this->message]);
    }
}
