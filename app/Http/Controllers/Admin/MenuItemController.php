<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\MenuItem;

class MenuItemController extends Controller
{
    static function destroyFromList ($itemsOriginal, $itemsForRemove = [])
    {
        // Loop for check for remove
        foreach ($itemsForRemove AS $item) {
            foreach( $itemsOriginal AS $itemOriginal) {
                if ($itemOriginal->id === $item['id']) {
                    $itemOriginal->forceDelete();
                }
            }
        }
    }
    
    static function store ($item, $menu, $locale)
    {
        $params = [
            'menu_id' => $menu->id,
            'lang' => $locale,
            'label' => $item['label'],
            'type' => $item['type'],
            'page_id' => $item['page_id'],
            'url_external' => $item['url_external'],
            'priority' => $item['priority'],
            'user_id' => auth()->user()->id
        ];
        MenuItem::create($params);
    }
    
    static function save ($params)
    {
        $item = MenuItem::find($params['id']);
        $item->label = $params['label'];
        $item->type = $params['type'];
        $item->page_id = $params['page_id'];
        $item->url_external = $params['url_external'];
        $item->priority = $params['priority'];
        $item->save();
    }
}
