<?php

use Faker\Generator as Faker;

$factory->define(App\MenuItem::class, function (Faker $faker) {
    return [
        'label' => $faker->word,
        'priority' => $faker->numberBetween(0, 10)
    ];
});
