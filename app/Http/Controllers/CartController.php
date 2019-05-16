<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;

class CartController extends Controller
{
    // 商品入库
    public function add(AddCartRequest $request)
    {
        $user   = $request->user();
        $skuId  = $request->input('sku_id');
        $amount = $request->input('amount');

        // 从数据库中查询该商品是否已经在购物车中
        if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {

            // 如果存在则直接叠加商品数量
            $cart->update([
                'amount' => $cart->amount + $amount,
            ]);
        } else {

            // 否则创建一个新的购物车记录
            $cart = new CartItem(['amount' => $amount]);
            $cart->user()->associate($user);
            $cart->productSku()->associate($skuId);
            $cart->save();
        }

        return [];
    }

    // 购物车列表
    public function index(Request $request)
    {
        // 为了解决 N+1 问题在获取关联数据时使用 with 预加载
        // 而Laravel支持通过 . 的方式加载多层级的关联关系
        // 于是就有了 with(['productSku.product']) 这段代码
        $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();

        return view('cart.index',compact('cartItems'));
    }
    // 移除购物车商品
    public function remove(ProductSku $sku,Request $request)
    {
        $request->user()->cartItems()->where('product_sku_id',$sku->id)->delete();

        return [];
    }
}
