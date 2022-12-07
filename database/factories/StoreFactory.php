<?php

use App\Address;
use App\Guild;
use App\Store;
use App\User;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'slogan' => $faker->sentence(6),
        'address_id' => factory(Address::class)->create()->id,
        'guild_id' => factory(Guild::class)->create()->id,
        'slug' => $faker->slug,
        'name' => $faker->name,
        'user_name' => $faker->userName,
        'min_pay' => rand(0 , 10000000),
        'status' => 'approved',
        'visible' => 1,
        'about' => $faker->paragraph(1),
        'phone_number' => $faker->phoneNumber,
        // 'thumbnail_photo' => $faker->image(null , 200 , 200),
        'phone_number_visibility' => 'show',
        'mobile_visibility' => 'show',
        'reject_reason' => $faker->paragraph(1),
        'total_hits' => 1,
        'pay_type' => 'online',
        'activity_type' => 'country',
        'shaba_code' => $faker->creditCardNumber,
        'notified_finishing_subscription_plan' => 1,
    ];
});


$faker->state(Store::class , 'status_rejected' , [
    'status' => 'rejected',
]);
$faker->state(Store::class , 'status_pending' , [
    'status' => 'pending',
]);
$faker->state(Store::class , 'status_deleted' , [
    'status' => 'deleted',
]);

$faker->state(Store::class , 'invisible' , [
    'visible' => 0
]);
$faker->state(Store::class , 'phone_number_invisible' , [
    'phone_number_visibility' => 'hide'
]);


$faker->state(Store::class , 'mobile_invisible' , [
    'mobile_visibility' => 'hide'
]);

$faker->state(Store::class , 'pay_type_postal' , [
    'pay_type' => 'postal'
]);
$faker->state(Store::class , 'pay_type_both' , [
    'pay_type' => 'both'
]);

$faker->state(Store::class , 'activity_type_province' , [
    'activity_type' => 'province'
]);


$faker->state(Store::class , 'notified_finishing_subscription_plan_0' , [
    'notified_finishing_subscription_plan' => 0
]);



