<?php

use Faker\Generator as Faker;

$factory->define(App\CategoryLocale::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'slug' => str_slug($title, '-'),
        'title' => $title,
        'description' => $faker->paragraph,
        'layout' => $faker->word,
        'content' => json_encode([
            $faker->word => $faker->randomDigitNotNull,
            $faker->word => $faker->word
        ]),
        'seo_title' => $faker->sentence,
        'seo_description' => $faker->paragraph,
        'seo_keywords' => json_encode([
            $faker->word,
            $faker->word,
            $faker->word
        ]),
    ];
});
