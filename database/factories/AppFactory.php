<?php

use Faker\Generator as Faker;
use App\Http\Helpers\AppHelper AS AppHelper;

$factory->define(App\App::class, function (Faker $faker) {
	$type = AppHelper::getTypes();
	$status = AppHelper::getStatus();

	return [
        'version' => $faker->word,
        'vue_component' => $faker->word,
        'type' => $type[$faker->numberBetween(0, COUNT($type) - 1)]['key'],
        'status' => $status[$faker->numberBetween(0, COUNT($status) - 1)]['key']
    ];
});
