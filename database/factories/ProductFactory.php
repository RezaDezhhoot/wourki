<?php

use App\Category;
use App\ProductSeller;
use App\Store;
use Faker\Generator as Faker;

$factory->define(ProductSeller::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'price' => rand(1000 , 900000000),
        'discount' => rand(0 , 100),
        'quantity' => rand(1 , 100),
        'visible' => 1,
        'category_id' => factory(Category::class)->create()->id,
        'store_id' => factory(Store::class)->create()->id,
        'status' => 'approved',
        'hint' => 1,
        'is_vip' => 0,
        'product_without_photo_notified' => 1,
        'guarantee_mark' => 1,
        'shipping_price_to_tehran' => rand(1000 , 100000),
        'shipping_price_to_other_towns' => rand(1000 , 100000),
        'deliver_time_in_tehran' => rand(1 , 10),
        'deliver_time_in_other_towns' => rand(1 , 10),
    ];
});

$factory->state(ProductSeller::class , 'invisible' , [
    'visible' => 0
]);

$factory->state(ProductSeller::class , 'status_rejected' , [
    'status' => 'rejected'
]);
$factory->state(ProductSeller::class , 'status_deleted' , [
    'status' => 'deleted'
]);

$factory->state(ProductSeller::class , 'status_pending' , [
    'status' => 'pending'
]);
$factory->state(ProductSeller::class , 'vip' , [
    'is_vip' => 1
]);

