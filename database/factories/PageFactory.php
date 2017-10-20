<?php

use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    return [
        'user_id' => App\User::role('admin')->get()->random()->id
    ];
});
