<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    // 可填充字段
    protected $fillable = [
        'province', 'city', 'district', 'addresss', 'zip',
        'contact_name', 'contact_phone','last_used_at',
    ];

    // 时间日期类型转换
    protected $dates = ['last_used_at'];

    // 一个地址对应一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 属性访问器 获取完整地址
    public function getFullAddressAttribute()
    {
        return "{$this->provice}{$this->city}{$this->district}{$this->address}";
    }
}
