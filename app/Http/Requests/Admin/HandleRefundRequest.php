<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class HandleRefundRequest extends Request
{
    public function rules()
    {
        return [
            'agress' => ['required','boolean'],
            // 拒绝退款时需要填写理由
            'reason' => ['required_if:agree,false']
        ];
    }
}
