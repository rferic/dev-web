<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'user_id' => App\User::all()->random()->id,
        'status' => $faker->numberBetween(0, 3),
        'origin' => $faker->word,
        'subject' => $faker->word,
        'text' => $faker->paragraph
    ];
});
