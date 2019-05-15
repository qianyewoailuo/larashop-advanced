<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Http\Requests\UserAddressRequest;

class UserAddressesController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }
    // 展示创建收货地址
    public function create(UserAddress $address)
    {
        return view('user_addresses.create_and_edit', compact('address'));
    }

    // 存储创建收货地址
    public function store(UserAddressRequest $request)
    {
        $request->user()
            ->addresses()
            ->create($request->only([
                'province',
                'city',
                'district',
                'address',
                'zip',
                'contact_name',
                'contact_phone',
            ]));

        // session()->flash('success', '地址创建成功');   // 可以使用with()方法直接携带闪存
        return redirect()->route('user_addresses.index')->with('success', '收货地址创建成功');
    }
}
