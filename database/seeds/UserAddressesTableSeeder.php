<?php

use Illuminate\Database\Seeder;
use App\Models\UserAddress;
use App\Models\User;
class UserAddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 旧方法依次序创建
        // 生成数据
        // $addresses = factory(UserAddress::class)
        //                 ->times('10')
        //                 ->make()
        //                 ->each(function($address,$index){
        //                     $address->user_id = mt_rand(1,3);
        //                 });
        // 数据入库
        // UserAddress::insert($addresses->toArray());

        // 新方法: 直接创建数据
        User::all()->each(function (User $user) {
            // 每个用户随机生成 1-3 个地址
            factory(UserAddress::class, random_int(1, 3))->create(['user_id' => $user->id]);
        });
    }
}
