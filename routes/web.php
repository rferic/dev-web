<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::post('/cache/remove', 'CacheController@forget')->middleware('role:admin')->name('cache.remove');
Route::post('/cache/clear', 'CacheController@flush')->middleware('role:admin')->name('cache.clear');

Route::group(
    [
        'prefix' => 'dev',
        'middleware' => ['role:admin']
    ],
    function ()
    {
        Route::get('/', 'Admin\DashboardController@index')->name('admin.dashboard');
        /*
         * Admin Menu Routes
         */
        Route::get('/menus', 'Admin\MenuController@index')->name('admin.menu.index');
        Route::get('/menus/create', 'Admin\MenuController@create')->name('admin.menu.create');
        Route::get('/menus/{menu}', 'Admin\MenuController@detail')->name('admin.menu.detail');
        Route::get('/menus/{menu}/trash', 'Admin\MenuController@trash')->name('admin.menu.trash');
        Route::get('/menus/{menu}/restore', 'Admin\MenuController@restore')->name('admin.menu.restore');
        Route::get('/menus/{menu}/destroy', 'Admin\MenuController@destroy')->name('admin.menu.destroy');
        Route::post('/menus', 'Admin\MenuController@store')->name('admin.menu.store');
        Route::post('/menus/{menu}/getItemsLocale', 'Admin\MenuController@getItemsLocale')->name('admin.menu.getItemsLocale');
        Route::post('/menus/{menu}/save', 'Admin\MenuController@save')->name('admin.menu.save');
        Route::put('/menus/{menu}', 'Admin\MenuController@edit')->name('admin.menu.edit');


        /*
         * Admin Pages Routes
         */
        Route::get('/pages', 'Admin\PageController@index')->name('admin.pages');
        Route::get('/pages/create', 'Admin\PageController@create')->name('admin.page.create');
        Route::get('/pages/{page}', 'Admin\PageController@detail')->name('admin.page.detail');
        Route::get('/pages/{page}/trash', 'Admin\PageController@trash')->name('admin.page.trash');
        Route::get('/pages/{page}/restore', 'Admin\PageController@restore')->name('admin.page.restore');
        Route::get('/pages/{page}/destroy', 'Admin\PageController@destroy')->name('admin.page.destroy');
        Route::post('/pages/getter', 'Admin\PageController@getter')->name('admin.pages.getter');
        Route::post('/pages/store', 'Admin\PageController@store')->name('admin.page.store');
        Route::post('/pages/{page}/update', 'Admin\PageController@update')->name('admin.page.update');
        Route::post('/pages/{page}/destroy-pagelocale', 'Admin\PageController@destroyPageLocale')->name('admin.pagelocale.destroyPageLocale');
        Route::post('/pages/{page}/store-content', 'Admin\PageController@storeContent')->name('admin.content.store');
        Route::post('/pages/{page}/update-content', 'Admin\PageController@updateContent')->name('admin.content.update');
        Route::post('/pages/{page}/destroy-content', 'Admin\PageController@destroyContent')->name('admin.content.destroy');

        /*
         * Admin Apps Routes
         */
        Route::get('apps', 'Admin\AppController@index')->name('admin.apps');
        Route::post('/apps/store', 'Admin\AppController@store')->name('admin.app.store');
        Route::post('/apps/{app}/update', 'Admin\AppController@update')->name('admin.app.update');
        Route::post('/apps/{app}/destroy', 'Admin\AppController@destroy')->name('admin.app.destroy');

        /*
         * Admin Images App Routes
         */
        Route::post('/app-images/upload', 'Admin\AppImageController@upload')->name('admin.app-images.upload');
        Route::post('/app-images/destroy', 'Admin\AppImageController@destroy')->name('admin.app-images.destroy');

        /*
         * Admin Users Private App Routes
         */
        Route::get('/apps-private-users', 'Admin\AppController@indexPrivateUsers')->name('admin.apps.users');
        Route::post('/apps-private-users/{app}/sync', 'Admin\AppController@sync')->name('admin.apps.users.sync');
        Route::post('/apps-private-users/{app}/revoke', 'Admin\AppController@revoke')->name('admin.apps.users.revoke');
        
        /*
         * Admin Profile Routes
         */
        Route::get('/profile', 'Admin\ProfileController@index')->name('admin.profile');
        Route::post('/profile/validate-email-is-free', 'Admin\ProfileController@emailIsFree')->name('admin.profile.emailIsFree');
        Route::post('/profile/update', 'Admin\ProfileController@update')->name('admin.profile.update');
        Route::post('/profile/reset', 'Admin\ProfileController@reset')->name('admin.profile.reset');
        
        /*
         * Admin Users Routes
         */
        Route::get('/users', 'Admin\UserController@index')->name('admin.users');
        Route::get('/users/create', 'Admin\UserController@create')->name('admin.user.create');
        Route::get('/users/{user}', 'Admin\UserController@detail')->name('admin.user');

        /*
         * Admin Admins Routes
         */
        Route::get('/admins', 'Admin\AdminController@index')->name('admin.admins');
        Route::get('/admins/create', 'Admin\AdminController@create')->name('admin.admin.create');
        Route::get('/admins/{user}', 'Admin\AdminController@detail')->name('admin.admin');

        /*
         * Admin Messages Routes
         */
        Route::get('/messages', 'Admin\MessageController@index')->name('admin.messages');
        Route::post('/messages/getter', 'Admin\MessageController@getter')->name('admin.messages.getter');
        Route::post('/messages/get-pendings', 'Admin\MessageController@getPendings')->name('admin.messages.getPendings');
        Route::post('/messages/{message}/update', 'Admin\MessageController@update')->name('admin.messages.update');
        Route::post('/messages/{message}/trash', 'Admin\MessageController@trash')->name('admin.messages.trash');
        Route::post('/messages/{message}/destroy', 'Admin\MessageController@destroy')->name('admin.messages.destroy');
    }
);

Route::group(
    [
    	'prefix' => LaravelLocalization::setLocale(),
    	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'httpCache:60' ]
    ],
    function()
    {
    	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    	Route::get('/', function()
    	{
            return view('welcome');
    	})->name('home');
        
        Route::get('/profile', function()
    	{
            return view('profile');
    	})->name('profile');
    }
);
