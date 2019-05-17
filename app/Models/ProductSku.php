<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;

class ProductSku extends Model
{
    protected $fillable = ['title', 'description', 'price', 'stock'];
    // 与商品一对一关联
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // 减库存
    public function decreaseStock($amount)
    {
        if($amount < 0){
            throw new InternalException('减库存不能减少于0');
        }
        // 创建一个数据库查询构造器,写操作时会返回受影响的行数
        // 而ORM查询构造器只会返回true|false
        // 而通过返回受影响的行数可以判定商品库存是否不足
        // 最终$sql = update product_skus set stock = stock - $amount where id = $id and stock >= $amount
        return $this->newQuery()->where('id',$this->id)
                            ->where('stock','>=',$amount)
                            ->decrement('stock',$amount);
    }
    // 增库存
    public function addStock($amount)
    {
        if($amount < 0){
            throw new InternalException('加库存不可少于0');
        }
        $this->increment('stock',$amount);

    }
}
