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
        $items = Input::get('items');
        $itemsForRemove = Input::get('itemsForRemove');
        $itemsOriginal = $menu->items()->where('lang', $locale)->get();

        // Loop for check for remove
        foreach ($itemsForRemove AS $item) {
            foreach( $itemsOriginal AS $itemOriginal) {
                if ($itemOriginal->id === $item['id']) {
                    $itemOriginal->forceDelete();
                }
            }
        }

        // Loop for check if is needsted create / update
        foreach ($items AS $item) {
            // Check if is a new item
            if (!isset($item['id']) || is_null($item['id'])) {
                // Create a new item
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
            } else {
                foreach ($itemsOriginal AS $itemOriginal) {
                    if ($itemOriginal->id === $item['id']) {
                        if ((isset($item['edit']) && $item['edit'] === true) || $item['priority'] !== $itemOriginal->priority) {
                            // Update a item
                            $itemOriginal = MenuItem::find($item['id']);
                            $itemOriginal->label = $item['label'];
                            $itemOriginal->type = $item['type'];
                            $itemOriginal->page_id = $item['page_id'];
                            $itemOriginal->url_external = $item['url_external'];
                            $itemOriginal->priority = $item['priority'];
                            $itemOriginal->save();
                        }
                    }
                }
            }
        }

        return Response::json(true);
    }
}
