<?php

use App\Province;
use Faker\Generator as Faker;

$factory->define(Province::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'deleted' => 0
    ];
});

$factory->state(Province::class , 'deleted' , [
    'deleted' => 1,
]);