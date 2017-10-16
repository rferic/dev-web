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

    public function trash (Page $page)
    {
        $page->delete();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Page has been moved to trash')])
            ->with('currentPanel', 'trash');
    }

    public function destroy (Request $request, $id)
    {
        Page::withTrashed()->findOrFail($id)->forceDelete();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Page has been removed')])
            ->with('currentPanel', 'trash');
    }

    public function restore (Request $request, $id)
    {
        Page::withTrashed()->findOrFail($id)->restore();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Page has been restored')]);
    }
}
