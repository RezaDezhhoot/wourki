<?php

use App\Bill;
use App\BillItem;
use App\ProductSeller;
use Faker\Generator as Faker;

$factory->define(BillItem::class, function (Faker $faker) {
    $product = factory(ProductSeller::class)->create();
    return [
        'bill_id' => factory(Bill::class)->create()->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => $product->price,
        'discount' => $product->discount,
        'quantity' => rand(1, 20),
        'shipping_price' => $product->shipping_price_to_tehran,
    ];
});

