<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CouponCode extends Model
{
    // 定义常量表示支持的优惠券类型
    const TYPE_FIXED   = 'fixed';
    const TYPE_PERCENT = 'percent';

    public static $typeMap = [
        self::TYPE_FIXED    => '固定金额',
        self::TYPE_PERCENT  => '比例金额',
    ];

    protected $fillable = [
        'name',
        'code',
        'type',
        'value',
        'total',
        'used',
        'min_amount',
        'not_before',
        'not_after',
        'enabled',
    ];

    protected $casts = [
        'enabled'  =>  'boolean',
    ];

    protected $dates = [
        'not_before',
        'not_after'
    ];
    // 添加新虚拟字段到模型
    // 注意首先必须先定义该新增属性的访问器
    // 例如 getDescriptionAttribute
    protected $appends = [
        'description'
    ];

    public function getDescriptionAttribute()
    {
        $str = '';

        if ($this->min_amount > 0) {
            $str = '满' . str_replace('.00', '', $this->min_amount);
        }
        if ($this->type === self::TYPE_PERCENT) {
            return $str . '优惠' . str_replace('.00', '', $this->value) . '%';
        }

        return $str . '减' . str_replace('.00', '', $this->value);
    }

    public static function findAvailableCode($length = 16)
    {
        do {
            // 生成一个指定长度的随机字符串并转成大写
            $code = strtoupper(Str::random($length));
            // 如果生成的优惠码已存在就继续循环
        }while(self::query()->where('code',$code)->exists());

        return $code;
    }
}
