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

        Route::get('/menus', 'Admin\MenuController@index')->name('admin.menu.index');
        Route::get('/menus/create', 'Admin\MenuController@create')->name('admin.menu.create');
        Route::get('/menus/{menu}', 'Admin\MenuController@detail')->name('admin.menu.detail');
        Route::get('/menus/{menu}/trash', 'Admin\MenuController@trash')->name('admin.menu.trash');
        Route::get('/menus/{menu}/restore', 'Admin\MenuController@restore')->name('admin.menu.restore');

        Route::post('/menus', 'Admin\MenuController@store')->name('admin.menu.store');
        Route::post('/menus/{menu}/getItemsLocale', 'Admin\MenuController@getItemsLocale')->name('admin.menu.getItemsLocale');
        Route::post('/menus/{menu}/save', 'Admin\MenuController@save')->name('admin.menu.save');
        Route::put('/menus/{menu}', 'Admin\MenuController@edit')->name('admin.menu.edit');
        Route::delete('/menus/{menu}/destroy', 'Admin\MenuController@destroy')->name('admin.menu.destroy');

        Route::get('/menus-items/{menus}', 'Admin\MenuItemController@create')->name('admin.menu.items.create');
        Route::get('/menus-items/{item}', 'Admin\MenuItemController@detail')->name('admin.menu.items.detail');
        Route::post('/menus-items', 'Admin\MenuItemController@store')->name('admin.menu.items.store');

        Route::get('/pages', 'Admin\PageController@index')->name('admin.pages');
        Route::get('/pages{page}', 'Admin\PageController@detail')->name('admin.page');
        Route::post('/pages/listiing', 'Admin\PageController@listing')->name('admin.pages.list');

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
