<?php

use App\Address;
use App\Bill;
use App\Store;
use App\User;
use Faker\Generator as Faker;
use function Complex\add;

$factory->define(Bill::class, function (Faker $faker) {
    $address = factory(Address::class)->create();
    return [
        'store_id' => factory(Store::class)->create()->id,
        'user_id' => factory(User::class)->create()->id,
        'address_id' => $address->id,
        'address' => $address->address,
        'customer_lat' => $address->latitude,
        'customer_lng' => $address->longitude,
        'pay_type' => 'online',
        'pay_id' => $faker->word,
        'status' => 'pending',
        'reject_reason' => $faker->paragraph,
        'delivery_days' => rand(0 , 10),
    ];
});
$factory->state(Bill::class , 'pay_type_wallet' , [
    'pay_type' => 'wallet'
]);

$factory->state(Bill::class , 'status_delivered' , [
    'status' => 'delivered'
]);

$factory->state(Bill::class , 'status_rejected' , [
    'status' => 'rejected'
]);

$factory->state(Bill::class , 'status_paid_back' , [
    'status' => 'paid_back'
]);

$factory->state(Bill::class , 'status_approved' , [
    'status' => 'approved'
]);


