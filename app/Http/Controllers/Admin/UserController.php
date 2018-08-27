<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\User;

class UserController extends Controller
{
    private $role = 'public';

    public function index ()
    {
    	$role = $this->role;
        $users = User::role($role)->with('comments')->get();

        return view('admin.user.index', compact('users', 'role'));
    }

    public function detail (User $user)
    {
    	return view('admin.user.form', compact('user'));
    }

    public function create ()
    {

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
