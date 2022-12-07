<?php

use App\Category;
use App\Guild;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'guild_id' => factory(Guild::class)->create()->id,
        'icon' => $faker->image(null , 100 , 100)
    ];
});
