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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', function () {
    return redirect('/login');
});
Route::middleware('auth:web')->group(function () {
    Route::namespace('Backend')->group(function () {

        Route::resource('categories', 'CategoryController');
        Route::resource('events', 'EventController');
        Route::resource('visitors', 'VisitorController');
        Route::resource('programs', 'ProgramController');
        Route::resource('companies', 'CompanyController');
        Route::resource('generals', 'GeneralController');
        Route::resource('details', 'DetailController');
        Route::resource('rules', 'RuleController');
        Route::get('logout', 'VisitorController@logout');
        Route::get('access', 'RuleController@get_Access');
        Route::get('search/{id?}', 'EventController@search');
        Route::get('upcoming', 'EventController@upcoming');

        Route::get('/{path}', 'PagesController@index')->where( 'path', "([A-z\d\-/_.]+)?" );
    });
});
