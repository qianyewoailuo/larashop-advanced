<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    $image = $faker->randomElement([

        "https://s2.ax1x.com/2019/05/23/VPU8XT.jpg",
        "https://s2.ax1x.com/2019/05/23/VParPs.jpg",
        "https://s2.ax1x.com/2019/05/23/VPBlOP.jpg",
        "https://s2.ax1x.com/2019/05/23/VPBQyt.jpg",
        "https://s2.ax1x.com/2019/05/23/VPawVg.jpg",

    ]);

    // 从数据库中随机取一个类目
    $category = \App\Models\Category::query()->where('is_directory', false)->inRandomOrder()->first();

    return [
        'title'        => $faker->word,
        'description'  => $faker->sentence,
        'image'        => $image,
        'on_sale'      => true,
        'rating'       => $faker->numberBetween(0, 5),
        'sold_count'   => 0,
        'review_count' => 0,
        'price'        => 0,
        // 将取出的类目 ID 赋给 category_id 字段
        // 如果数据库中没有类目则 $category 为 null，同样 category_id 也设成 null
        'category_id'  => $category ? $category->id : null,
    ];
});
