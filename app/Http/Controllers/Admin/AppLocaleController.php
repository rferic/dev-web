<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Core\App;
use App\Models\Core\AppLocale;

class AppLocaleController extends Controller
{
    static function store ( App $app, $localeData )
    {
        return AppLocale::create([
            'app_id' => $app->id,
            'lang' => $localeData['lang'],
            'slug' => $localeData['slug'],
            'title' => $localeData['title'],
            'description' => $localeData['description'],
        ])->id;
    }
    
    static function save ( AppLocale $locale, $localeData )
    {
        $locale->slug = $localeData['slug'];
        $locale->title = $localeData['title'];
        $locale->description = $localeData['description'];
        
        $locale->save();
    }
}