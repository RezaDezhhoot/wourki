<?php

use App\Guild;
use Faker\Generator as Faker;

$factory->define(Guild::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'pic' => $faker->image(null , 100 , 100 , null , true),
    ];
});

