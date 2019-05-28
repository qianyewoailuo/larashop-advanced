<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    // 可填充字段
    protected $fillable = [
        'province', 'city', 'district', 'address', 'zip',
        'contact_name', 'contact_phone','last_used_at',
    ];

    // 时间日期类型转换
    protected $dates = ['last_used_at'];

    // 将访问器的属性添加到模型中,一旦属性被添加到 appends 清单，便会将模型中的数组和 JSON 这两种形式都包含进去
    // 参考 https://learnku.com/docs/laravel/5.5/eloquent-serialization/1337#appending-values-to-json
    protected $appends = ['full_address'];

    // 一个地址对应一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 属性访问器 获取完整地址
    public function getFullAddressAttribute()
    {
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
