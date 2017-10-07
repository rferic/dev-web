<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

use App\MenuItem;

class MenuItemController extends Controller
{
    public function create ()
    {

    }

    public function detail ()
    {

    }

    public function destroy (Request $request, MenuItem $item)
    {
        //$item->forceDelete();
        return Response::json(true);
    }
}
