<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\App;
use App\Http\Helpers\AppHelper;

class AppController extends Controller
{
    public function index()
    {
        $apps = App::with('users', 'images')->get();
        $types = AppHelper::getTypes();
        $status = AppHelper::getStatus();

        return view('admin.app.index', compact('apps', 'types', 'status'));
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, App $app)
    {
        //
    }

    public function destroy(App $app)
    {
        //
    }
}
