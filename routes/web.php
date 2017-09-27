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
        Route::get('/', function ()
        {
            return view('admin.home');
        });
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
