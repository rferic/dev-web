<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;

use App\MenuItem;

use App\Menu;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MenuController extends Controller
{
    public function index ()
    {
        $menus = Menu::with('author')->get();
        $menusTrashed = Menu::onlyTrashed()->with('author')->get();

        return view('admin.menu.index', compact('menus', 'menusTrashed'));
    }

    public function create ()
    {
        return view('admin.menu.form');
    }

    public function detail (Menu $menu)
    {
        $itemsLocale = [];
        $items = $menu->items()->get();

        foreach (LaravelLocalization::getSupportedLanguagesKeys() AS $locale) {
            $itemsLocale[$locale] = [];
        }

        foreach ($items AS $item) {
            if (isset($itemsLocale[$item->lang])) {
                array_push($itemsLocale[$item->lang], $item);
            }
        }

        return view('admin.menu.form', compact('menu', 'itemsLocale'));
    }

    public function store (MenuRequest $request)
    {
        $request->request->add(['user_id' => auth()->user()->id]);
        $menu = Menu::create($request->input());
        return redirect(route('admin.menu.detail', $menu->id))->with('message', __('Menu has been created'));
    }

    public function edit (MenuRequest $request, Menu $menu)
    {
        $menu->name = $request->input('name');
        $menu->description = $request->input('description');
        $menu->save();

        return redirect(route('admin.menu.detail', $menu->id))->with('message', ['class' => 'alert-success', 'content' => __('Menu has been updated')]);
    }

    public function trash (Menu $menu)
    {
        $menu->delete();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Menu has been moved to trash')])
            ->with('currentPanel', 'trash');
    }

    public function destroy (Request $request, $id)
    {
        Menu::withTrashed()->findOrFail($id)->forceDelete();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Menu has been removed')])
            ->with('currentPanel', 'trash');
    }

    public function restore (Request $request, $id)
    {
        Menu::withTrashed()->findOrFail($id)->restore();

        return back()
            ->with('message', ['class' => 'alert-success', 'content' => __('Menu has been restored')]);
    }
}
