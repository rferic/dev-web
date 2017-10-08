<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\PageLocale;

class PageController extends Controller
{
    public function list (Request $request)
    {
        $locale = Input::get('locale');
        $pages = PageLocale::where('lang', $locale);
        return Response::json($pages->get());
    }
}
