<?php
namespace App\Http\Helpers;

use Illuminate\Support\Facades\Lang;

class PageHelper
{
    static function prepareList ($pages)
    {
        $pagesList = [];

        foreach ($pages AS $page) {
            $defaultLocale = [
                'id' => $page->id,
                'author' => $page->author,
                'updated_at' => $page->updated_at,
                'created_at' => $page->created_at,
                'locales' => [],
                'slugs' => []
            ];

            foreach ($page->locales AS $locale) {
                if (!isset($defaultLocale['title']) || Lang::getLocale() === $locale->lang) {
                    $defaultLocale['title'] = $locale->title;
                }

                $defaultLocale['layouts'][$locale->lang] = $locale->layout;
                $defaultLocale['slugs'][$locale->lang] = $locale->slug;
            }

            array_push($pagesList, $defaultLocale);
        }

        return $pagesList;
    }
}
