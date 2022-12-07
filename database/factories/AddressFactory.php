<?php

use App\Address;
use App\City;
use App\User;
use Faker\Generator as Faker;

$factory->define(Address::class, function (Faker $faker) {
    return [
        'user_id' => factory(User::class)->create()->id,
        'city_id' => factory(City::class)->create()->id,
        'address' => $faker->address,
        'postal_code' => $faker->postcode,
        'phone_number' => $faker->phoneNumber,
        'type' => array_rand(['home' , 'store' , 'warehouse']),
        'status' => 'active',
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});
$factory->state(Address::class , 'deleted' , [
    'status' => 'deleted'
]);