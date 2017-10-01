<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Menu;

class MenuController extends Controller
{
    public function index ()
    {
        $menus = Menu::with('author')->paginate(100);
        $menusTrashed = Menu::onlyTrashed()->with('author')->paginate(100);

        return view('admin.menus.index', compact('menus', 'menusTrashed'));
    }

    public function detail (Menu $menu)
    {
        $locales = $menu->locales();
        dd($locales);
        return view('admin.menus.detail');
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
