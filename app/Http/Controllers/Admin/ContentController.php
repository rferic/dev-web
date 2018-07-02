<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Content;

class ContentController extends Controller
{
    static function store($data)
    {
        $params = [
            'user_id' => auth()->user()->id,
            'page_locale_id' => $data['page_locale_id'],
            'key' => $data['key'],
            'id_html' => $data['id_html'],
            'class_html' => $data['class_html'],
            'header_inject' => $data['header_inject'],
            'footer_inject' => $data['footer_inject'],
            'priority' => $data['priority']
        ];
        
        return Content::create($params)->id;
    }

    static function save($data, $id)
    {
        $content = Content::find($data['id']);
        
        $content->page_locale_id = $data['page_locale_id'];
        $content->key = $data['key'];
        $content->id_html = $data['id_html'];
        $content->class_html = $data['class_html'];
        $content->header_inject = $data['header_inject'];
        $content->footer_inject = $data['footer_inject'];
        $content->priority = $data['priority'];
        
        $content->save();
    }

    static function destroy($id)
    {
        Content::withTrashed()->findOrFail($id)->forceDelete();
    }
}
