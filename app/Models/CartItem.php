<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    // 可填充字段
    protected $fillable = ['amount'];
    public $timestamps = false;
    // 一条购物车信息属于一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 一条购物车信息属于一个单品(sku)
    public function productSku()
    {
        return $this->belongsTo(ProductSku::class);
    }
}
