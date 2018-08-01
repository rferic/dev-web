<?php

use Faker\Generator as Faker;

$factory->define(App\AppLocale::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'app_id' => App\App::all()->random()->id,
        'slug' => str_slug($title, '-'),
        'title' => $title,
        'description' => $faker->paragraph
    ];
});
