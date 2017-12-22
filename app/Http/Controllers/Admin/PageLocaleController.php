<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MenuItemController;

use App\PageLocale;

class PageLocaleController extends Controller
{
    static function store ($page_id, $data, $locale) {
        $params = [
            'user_id' => auth()->user()->id,
            'page_id' => $page_id,
            'lang' => $locale,
            'slug' => $data['slug'],
            'title' => $data['title'],
            'description' => $data['description'],
            'layout' => $data['layout'],
            'options' => json_encode(new \stdClass),
            'seo_title' => $data['seo_title'],
            'seo_description' => !is_null($data['seo_description']) ? $data['seo_description'] : '',
            'seo_keywords' => json_encode(!empty($data['seo_keywords']) ? $data['seo_keywords'] : [])
        ];
        
        PageLocale::create($params);
    }
    
    static function save ($data, $locale) {
        $pagelocale = PageLocale::find($data['id']);
        
        $pagelocale->slug = $data['slug'];
        $pagelocale->title = $data['title'];
        $pagelocale->description = $data['description'];
        $pagelocale->layout = $data['layout'];
        $pagelocale->seo_title = $data['seo_title'];
        $pagelocale->seo_description = !is_null($data['seo_description']) ? $data['seo_description'] : '';
        $pagelocale->seo_keywords = json_encode(!empty($data['seo_keywords']) ? $data['seo_keywords'] : []);
        
        $pagelocale->save();
    }
}