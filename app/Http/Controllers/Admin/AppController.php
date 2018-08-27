<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

use App\Http\Controllers\Admin\AppLocaleController;
use App\Http\Controllers\Admin\AppImageController;

use App\Models\Core\App;
use App\Models\Core\User;

use App\Http\Helpers\AppHelper;

class AppController extends Controller
{
    public function index ()
    {
        $apps = App::with('locales', 'users', 'images')->get();
        $types = AppHelper::getTypes();
        $status = AppHelper::getStatus();

        return view('admin.app.index', compact('apps', 'types', 'status'));
    }

    public function indexPrivateUsers ()
    {
        $apps = App::where('type', 'private')->with('locales', 'users')->get();
        $users = User::role('public')->get();

        return view('admin.app.users', compact('apps', 'users'));
    }

    public function store (Request $request)
    {
        $appDate = Input::get('app');

        $appId = App::create([
            'status' => $appDate['status'],
            'type' => $appDate['type'],
            'version' => $appDate['version'],
            'vue_component' => $appDate['vue_component']
        ])->id;

        $app = App::findOrFail($appId);
        // Save/Store App locale
        $this->createLocales($app, Input::get('locales'));
        // Save/Store App images
        $this->updateImages($app, Input::get('images'));

        // Response
        $app->locales = $app->locales()->get();
        $app->images = $app->images()->get();
        $app->users = $app->users()->get();
        $routes = [
            [   
                'id' => $appId,
                'type' => 'routesAppUpdate',
                'route' => route('admin.app.update', $app->id)
            ],
            [
                'id' => $appId,
                'type' => 'routesAppDestroy',
                'route' => route('admin.app.destroy', $app->id)
            ]
        ];

        return Response::json(compact('app', 'routes'));
    }

    public function update (Request $request, App $app)
    {
        $appData = Input::get('app');
        // Update APP data
        $app->status = $appData['status'];
        $app->type = $appData['type'];
        $app->version = $appData['version'];
        $app->vue_component = $appData['vue_component'];
        $app->save();
        // Save/Store App locale
        $this->updateLocales($app, Input::get('locales'));
        // Clear App images
        $this->clearImages($app, Input::get('images'));
        // Save/Store App images
        $this->updateImages($app, Input::get('images'));

        // Response
        $app->locales = $app->locales()->get();
        $app->images = $app->images()->get();

        return Response::json(compact('app'));
    }

    public function destroy (Request $request, App $app)
    {
        $app->forceDelete();
        return Response::json(true);
    }

    // Sync user with App
    public function sync (Request $request, App $app)
    {
        $user = User::find(Input::get('user')['id']);
        $app->users()->detach($user->id);

        if ( $user->hasRole('public') ) {
            $app->users()->attach($user->id, [ 'active' => Input::get('active') ]);
            $users = $app->users()->get();
            return Response::json(compact('users'));
        }
    }

    // Revoke user with App
    public function revoke (Request $request, App $app)
    {
        $user = User::find(Input::get('user')['id']);
        $app->users()->detach($user->id);
        return Response::json(true);
    }

    // Store App Locale
    protected function createLocales (App $app, $localesData)
    {
        foreach ( $localesData AS $lang => $localeData ) {
            $localeData['lang'] = $lang;
            AppLocaleController::store($app, $localeData);
        }
    }

    // Save App locale
    protected function updateLocales (App $app, $localesData)
    {
        foreach ( $localesData AS $localeData ) {
            $findLocale = false;

            foreach ( $app->locales()->get() AS $locale ) {
                if ( $localeData['lang'] === $locale->lang ) {
                     // Save App locale
                    AppLocaleController::save($locale, $localeData);
                    $findLocale = true;
                    break;
                }
            }
            if ( !$findLocale ) {
                 // Store App locale
                AppLocaleController::store($app, $localeData);
            }
        }
    }

    // Clear App images
    protected function clearImages (App $app, $imagesData)
    {
        foreach ( $app->images()->get() AS $image ) {
            $findImage = false;

            foreach ( $imagesData AS $imageData ) {
                if ( $image->id === $imageData['id'] ) {
                    $findImage = true;
                    break;
                }
            }

            if ( !$findImage ) {
                $image->forceDelete();
            }
        }
    }

    // Save/Store App images
    protected function updateImages (App $app, $imagesData)
    {
        $images = $app->images()->get();

        // Save App images
        foreach ( $imagesData AS $imageData ) {
            $isNewAppImage = true;

            if ( !is_null($imageData['id']) ) {
                // Store
                foreach ( $images AS $image ) {
                    if ( $imageData['id'] === $image->id ) {
                        $isNewAppImage = false;
                        AppImageController::save($image, $imageData);
                        break;
                    }
                }
            }

            if ( $isNewAppImage ) {
                AppImageController::store($app, $imageData);
            }
        }
    }
}
