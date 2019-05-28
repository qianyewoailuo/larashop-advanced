<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\CrowdfundingProduct;
use App\Models\ProductSku;
use Illuminate\Validation\Rule;

class CrowdfundingOrderRequest extends request
{
    public function rules()
    {
        return [
            'sku_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductSku::find($value)) {
                        return $fail('该商品不存在');
                    }

                    // 众筹商品接口仅支持众筹商品的SKU
                    if ($sku->product->type !== Product::TYPE_CROWDFUNDING) {
                        return $fail('该商品不支持众筹');
                    }
                    // 是否上架
                    if (!$sku->product->on_sale) {
                        return $fail('该商品未上架');
                    }
                    // 还需要判断众筹状态是否正在众筹中
                    if ($sku->product->crowdfunding->status !== CrowdfundingProduct::STATUS_FUNDING) {
                        return $fail('该商品众筹已结束');
                    }
                    // 库存是否为空
                    if ($sku->stock === 0) {
                        return $fail('该商品已售完');
                    }
                    // 库存是否足够支持当前购物数量
                    if ($this->input('amount') > 0 && $sku->stock < $this->input('amount')) {
                        return $fail('当前商品库存不足');
                    }
                }
            ],
            'amount' => ['required', 'integer', 'min:1'],
            'address_id'  => [
                'required',
                Rule::exists('user_addresses', 'id')->where('user_id', $this->user()->id),
            ],
        ];
    }
}
