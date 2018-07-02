<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\Page;

use App\Http\Helpers\PageHelper;
use App\Http\Controllers\Admin\PageLocaleController;
use App\Http\Controllers\Admin\ContentController;

class PageController extends Controller
{
    public function index ()
    {
        $pages = PageHelper::prepareList(Page::with(['locales', 'author'])->get());
        $pagesTrashed = PageHelper::prepareList(Page::onlyTrashed()->with(['locales', 'author'])->get());

        return view('admin.page.index', compact('pages', 'pagesTrashed'));
    }
    
    public function detail (Page $page)
    {
        return view('admin.page.form', compact('page'));
    }
    
    public function create ()
    {
        return view('admin.page.form');
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
    
    public function update (Request $request, Page $page)
    {
        $pageLocaleData = Input::get('pageLocale');
        $locale = Input::get('locale');
        
        if (is_null($pageLocaleData['id']))
            PageLocaleController::store($page->id, $pageLocaleData, $locale);
        else
            PageLocaleController::save($pageLocaleData);
        
        return Response::json(true);
    }
    
    public function store (Request $request)
    {
        $pageLocaleData = Input::get('pageLocale');
        $locale = Input::get('locale');
        $request->request->add(['user_id' => auth()->user()->id]);
        $page = Page::create($request->input());
        
        PageLocaleController::store($page->id, $pageLocaleData, $locale);
        
        return Response::json(route('admin.page.detail', $page->id));
    }
    
    public function destroyPageLocale (Request $request, Page $page)
    {
        $locale = Input::get('locale');
        $pagelocale = $page->locales()->where('lang', $locale)->first();
        $pagelocale->forceDelete();
        
        return Response::json($page->locales()->count() < 1);
    }

    public function storeContent (Request $request, Page $page)
    {
        ContentController::store($request->content);
        return Response::json(true);
    }

    public function updateContent (Request $request, Page $page)
    {
        ContentController::save($request->content, $request->content['id']);
        return Response::json(true);
    }

    public function destroyContent (Request $request, $page)
    {
        ContentController::destroy($request->content);
        return Response::json(true);
    }
}
