<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','email_verified'
    ];

    // 类型自动转换
    protected $casts = [
        'email_verified' => 'boolean',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 一个用户对应多个地址
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    // 多个用户对应多个收藏商品
    public function favoriteProducts()
    {
        // 多对多关联并以创建时间降序排序
        return $this->belongsToMany(Product::class,'user_favorite_products')
                    ->withTimestamps()
                    ->orderBy('user_favorite_products.created_at','desc');
    }

    // 一个用户对应多个购物车信息
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
