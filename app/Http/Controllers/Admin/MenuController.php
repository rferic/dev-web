<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

use App\Menu;
use App\MenuItem;

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
        return view('admin.menu.form', compact('menu'));
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

    public function getItemsLocale (Request $request, Menu $menu)
    {
        $locale = Input::get('locale');
        $items = $menu->items()->where('lang', $locale)->with('page')->orderBy('priority', 'ASC');
        return Response::json($items->get(['id', 'label', 'type', 'page_id', 'url_external', 'priority']));
    }

    public function save (Request $request, Menu $menu)
    {
        $locale = Input::get('locale');
        $items = Input::get('items');

        $itemsOriginal = $menu->items()->where('lang', $locale)->get();

        // Loop for check if is needsted remove
        foreach ($itemsOriginal AS $itemOriginal) {
            $find = false;

            foreach ($items AS $item) {
                if (isset($item['id']) && !is_null($item['id']) && $item['id'] === $itemOriginal->id) {
                    $find = true;
                }
            }

            if (!$find) {
                $item->forceDelete();
            }
        }

        // Loop for check if is needsted create
        foreach ($items AS $item) {
            if (!isset($item['id']) && !is_null($item['id'])) {
                $params = [
                    'menu_id' => $menu->id,
                    'lang' => $locale,
                    'label' => $item['label'],
                    'type' => $item['type'],
                    'page_id' => $item['page_id'],
                    'url_external' => $item['url_external'],
                    'priority' => $item['priority']
                ];

                MenuItem::create($params);
            } else {
                $itemMenu = MenuItem::find($item['id']);
                $itemMenu->label = $item['label'];
                $itemMenu->type = $item['type'];
                $itemMenu->page_id = $item['page_id'];
                $itemMenu->url_external = $item['url_external'];
                $itemMenu->priority = $item['priority'];
                $itemMenu->save();
            }
        }

        return Response::json(true);
    }
}
