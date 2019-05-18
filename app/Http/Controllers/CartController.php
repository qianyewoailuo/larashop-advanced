<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddCartRequest;
use App\Models\CartItem;
use App\Models\ProductSku;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        // 自动解析注入 CartService 类
        // 之后我们会通过 Service 模式将之前的业务代码进行封装
        $this->cartService = $cartService;
    }

    // 商品入库
    public function add(AddCartRequest $request)
    {
        // $user   = $request->user();
        $skuId  = $request->input('sku_id');
        $amount = $request->input('amount');

        // 从数据库中查询该商品是否已经在购物车中
        // if ($cart = $user->cartItems()->where('product_sku_id', $skuId)->first()) {

        // 如果存在则直接叠加商品数量
        // $cart->update([
        // 'amount' => $cart->amount + $amount,
        // ]);
        // } else {

        // 否则创建一个新的购物车记录
        // $cart = new CartItem(['amount' => $amount]);
        // $cart->user()->associate($user);
        // $cart->productSku()->associate($skuId);
        // $cart->save();
        // }

        // 上述代码封装
        $this->cartService->add($skuId, $amount);

        return [];
    }

    // 购物车列表
    public function index(Request $request)
    {
        // 为了解决 N+1 问题在获取关联数据时使用 with 预加载
        // 而Laravel支持通过 . 的方式加载多层级的关联关系
        // 于是就有了 with(['productSku.product']) 这段代码
        // $cartItems = $request->user()->cartItems()->with(['productSku.product'])->get();
        // 上述代码封装
        $cartItems = $this->cartService->get();
        // 用户收货地址
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

        return view('cart.index', compact('cartItems', 'addresses'));
    }
    // 移除购物车商品
    public function remove(ProductSku $sku, Request $request)
    {
        // $request->user()->cartItems()->where('product_sku_id',$sku->id)->delete();
        // 上述代码封装
        $this->cartService->remove($sku->id);

        return [];
    }
}
