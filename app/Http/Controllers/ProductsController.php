<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Exceptions\InvalidRequestException;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // 创建一个查询构造器
        $builder = Product::query()->where('on_sale', true);

        // search模糊查询判断
        if ($search = $request->input('search', '')) {
            // 模糊查询条件
            $like = '%' . $search . '%';
            // 模糊查询商品标题、详情、SKU标题、SKU描述
            // 使用匿名函数目的在于将查询条件语句使用()包裹
            // 其中 orWhereHas 方法是将where应用到has存在关联关系查询上
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        // order商品排序查询
        if ($order = $request->input('order', '')) {
            // 开始order排序查询
            // 判断是否以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $mactch)) {
                // 如果字符串的开头是下面3个字符串之一表示合法排序
                if (in_array($mactch[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序字段以及排序方式进行构造排序
                    $builder->orderBy($mactch[1], $mactch[2]);
                }
            }
        }
        // 查询或排序的值返回到模板
        $filters = [
            'search' => $search,
            'order' => $order
        ];

        // 查询与排序结束,开始分页获取结果集
        $products = $builder->paginate(16);

        return view('products.index', compact('products', 'filters'));
    }

    public function show(Product $product, Request $request)
    {
        // 判断商品是否已经上架 如果没有上架则抛出异常
        if (!$product->on_sale) {
            throw new InvalidRequestException('商品未上架');
        }

        // 设置默认未收藏
        $favored = false;
        // 判断当前浏览者是否已登录
        if($user = $request->user()){
            // 查找当前登录用户的收藏中是否有当前id的商品
            // boolval是将结果转换为布尔类型
            // 多对多的find方法中的参数是关联的对象ID,这里找寻对应ID商品是否存在
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }


        // 默认单品属性选中展示
        $default_sku = $product->skus()->where('price', $product->price)->first();

        return view('products.show', compact('product', 'default_sku','favored'));
    }
    // 收藏商品
    public function favor(Product $product, Request $request)
    {
        $user = $request->user();
        if ($user->favoriteProducts()->find($product->id)) {
            // 如果该商品已收藏则不做任何操作
            // 这里的 return [] 是因为前段使用了ajax请求进行处理
            return [];
        }
        // attach() 方法将当前用户和此商品关联起来
        // attach() 方法的参数可以是模型的 id，也可以是模型对象本身，因此这里还可以写成 attach($product->id)
        $user->favoriteProducts()->attach($product);

        return [];
    }
    // 取消收藏商品
    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();
        $user->favoriteProducts()->detach($product);

        return [];
    }

    // 收藏商品列表
    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites',compact('products'));
    }
}
