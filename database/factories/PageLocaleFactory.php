<?php

use Faker\Generator as Faker;

$factory->define(App\PageLocale::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'user_id' => App\User::role('admin')->get()->random()->id,
        'slug' => str_slug($title, '-'),
        'title' => $title,
        'description' => $faker->paragraph,
        'layout' => $faker->word,
        'options' => json_encode([
            $faker->word => $faker->randomDigitNotNull,
            $faker->word => $faker->word
        ]),
        'seo_title' => $faker->sentence,
        'seo_description' => $faker->paragraph,
        'seo_keywords' => json_encode([
            $faker->word,
            $faker->word,
            $faker->word
        ])
    ];
});
