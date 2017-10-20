<?php

use Faker\Generator as Faker;

$factory->define(App\MenuItem::class, function (Faker $faker) {
    $random = (bool)random_int(0, 1);
    $pageLocale = App\PageLocale::all()->random();
    
    return [
        'user_id' => App\User::role('admin')->get()->random()->id,
        'menu_id' => App\Menu::all()->random()->id,
        'label' => $faker->word,
        'priority' => $faker->numberBetween(0, 10),
        'lang' => $pageLocale->lang,
        'type' => $random ? 'internal' : 'external',
        'page_locale_id' => $random ? $pageLocale->id : null,
        'url_external' => $random ? null : 'http://www.url.com'
    ];
});
