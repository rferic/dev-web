<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\Page;
use App\PageLocale;

use App\Http\Helpers\PageHelper;

class PageController extends Controller
{
    public function listing (Request $request)
    {
        $locale = Input::get('locale');
        $pages = PageLocale::where('lang', $locale);
        return Response::json($pages->get());
    }

    public function index ()
    {
        $pages = PageHelper::prepareList(Page::with(['locales', 'author'])->get());
        $pagesTrashed = PageHelper::prepareList(Page::onlyTrashed()->with(['locales', 'author'])->get());

        return view('admin.page.index', compact('pages', 'pagesTrashed'));
    }
}
