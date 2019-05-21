<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CartService
{
    public function get()
    {
        return Auth::user()->CartItems()->with(['productSku.product'])->get();
    }

    public function add($skuId,$amount)
    {
        $user = Auth::user();
        // 从数据库中查询该商品是否已经在购物车中
        if($item = $user->cartItems()->where('product_sku_id',$skuId)->first()){
            // 如果存在则直接叠加商品数量
            $item->update(['amount'=>$item->amount + $amount]);
        } else {
            // 否则创建一个新的购物车记录
            $item = new CartItem(['amount'=>$amount]);
            $item->user()->associate($user);
            $item->productSku()->associate($skuId);
            $item->save();
        }

        return $item;
    }

    public function remove($skuIds)
    {
        if(!is_array($skuIds)){
            // 如果传入的不是数组则转换为数组
            $skuIds = [$skuIds];
        }

        // 用户关联获取 carItem 查询构造器进行购物车信息删除
        Auth::user()->cartItems()->whereIn('product_sku_id',$skuIds)->delete();
    }
}