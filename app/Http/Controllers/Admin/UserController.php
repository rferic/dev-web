<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

class UserController extends Controller
{
    public function index ()
    {
    	$users = User::with('comments')->get();
    	$banned = false;

        return view('admin.user.index', compact('users', 'banned'));
    }

    public function indexBanned ()
    {
    	$users = User::onlyTrashed()->with('comments')->get();
    	$banned = true;
    	
        return view('admin.user.index', compact('users', 'banned'));
    }

    public function detail (User $user)
    {
    	return view('admin.user.form', compact('user'));
    }

    public function store ()
    {

    }

    public function save (User $user)
    {

    }

    public function banned (User $user)
    {
        
    }

    public function destroy (User $user)
    {

    }
}
