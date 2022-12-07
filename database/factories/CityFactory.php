<?php

use App\City;
use App\Province;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'province_id' => factory(Province::class)->create()->id,
        'name' => $faker->name,
        'deleted' => 0
    ];
});

$factory->state(City::class , 'deleted' , [
    'deleted' => 1,
]);