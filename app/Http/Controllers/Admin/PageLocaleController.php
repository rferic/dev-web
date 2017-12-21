<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\PageLocale;

class PageLocaleController extends Controller
{
    public static function store ($page_id, $data, $locale) {
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
    
    public static function save ($page_id, $data, $locale) {
        
    }
}