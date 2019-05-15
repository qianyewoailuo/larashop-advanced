<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];
    // 与商品一对一关联
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
