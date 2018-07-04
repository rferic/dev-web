<?php

use Faker\Generator as Faker;

$factory->define(App\App::class, function (Faker $faker) {
    return [
        'is_public' => $faker->boolean,
        'status' => $faker->numberBetween(0, 3),
        'title' => $faker->word,
        'description' => $faker->paragraph,
        'version' => $faker->word,
        'vue_component' => $faker->word
    ];
});
