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
        Route::post('/pages/store', 'Admin\PageController@store')->name('admin.page.store');
        Route::post('/pages/{page}/update', 'Admin\PageController@update')->name('admin.page.update');
        Route::post('/pages/{page}/destroy-pagelocale', 'Admin\PageController@destroyPageLocale')->name('admin.pagelocale.destroyPageLocale');

        /*
         * Admin Pages Locale Routes
         */
        Route::post('/pagelocales/{pagelocale}/destroy', 'Admin\PageController@destroy')->name('admin.pagelocale.destroy');
        
        /*
         * Admin Users Routes
         */
        Route::get('/users', 'Admin\UserController@index')->name('admin.users');
        Route::get('/users/{user}', 'Admin\UserController@detail')->name('admin.user');

        /*
         * Admin Admins Routes
         */
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
    	})->name('home');
        
        Route::get('/profile', function()
    	{
            return view('profile');
    	})->name('profile');
    }
);
