<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Core\User;

use App\Http\Helpers\PasswordHelper;

class ProfileController extends UserController
{
	public function index ()
	{
		$user = Auth::user();

		return view('admin.profile.index', compact('user'));
	}

	public function update ()
	{
		$user = Auth::user();
		$profile = Input::get('profile');

		$user->email = $profile['email'];
		$user->name = $profile['name'];

		$user->save();
		
        return Response::json([ 'result' => true ]);
	}

	public function reset ( Request $request )
	{
		$passwordCurrent = Input::get('passwordCurrent');
		$passwordNew = Input::get('password');
		$passwordNewConfirmation = Input::get('password_confirmation');

		if ( !empty($passwordCurrent) && !empty($passwordNew) && !empty($passwordNewConfirmation) ) {
			// validation
			if ( !( Hash::check( $passwordCurrent, Auth::user()->password ) ) ) {
				return Response::json([ 'result' => false ]);
			}

			$request->validate([ 'password' => PasswordHelper::validate() ]);

			// change password
			$user = Auth::user();
			$user->password = bcrypt($passwordNew);
			$user->save();

			return Response::json([ 'result' => true ]);
		}

		abort(500);
	}

	public function emailIsFree (Request $request)
	{
		$value = Input::get('value');

		if ( !empty($value) ) {
			$isRegistered = User::where('email', '=', $value)->count() > 0;
			$userCurrent = User::find(auth()->id());
			$isCurrent = $userCurrent->email === $value;

			return Response::json(['result' => !$isRegistered || $isCurrent]);
		}

		abort(500);
	}
}
