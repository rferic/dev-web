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

        Route::get('/menus', 'Admin\MenuController@index')->name('admin.menus');
        Route::get('/menus/{menu}', 'Admin\MenuController@detail')->name('admin.menu');
        Route::get('/menus/{menu}/trash', 'Admin\MenuController@trash')->name('admin.menu.trash');
        Route::get('/menus/{menu}/restore', 'Admin\MenuController@restore')->name('admin.menu.restore');
        Route::get('/menus/{menu}/destroy', 'Admin\MenuController@destroy')->name('admin.menu.destroy');

        Route::get('/pages', 'Admin\PageController@index')->name('admin.pages');
        Route::get('/pages{page}', 'Admin\PageController@detail')->name('admin.page');

        Route::get('/users', 'Admin\UserController@index')->name('admin.users');
        Route::get('/users/{user}', 'Admin\UserController@detail')->name('admin.user');

        Route::get('/admins', 'Admin\AdminController@index')->name('admin.admins');
        Route::get('/admins/{user}', 'Admin\AdminController@detail')->name('admin.admin');
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
    	});
    }
);
