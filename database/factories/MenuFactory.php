<?php

use Faker\Generator as Faker;

$factory->define(App\Menu::class, function (Faker $faker) {
    return [
        'user_id' => App\User::role('admin')->get()->random()->id,
        'name' => $faker->sentence,
        'description' => $faker->paragraph
    ];
});
