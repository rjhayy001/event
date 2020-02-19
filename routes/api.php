<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->group(function () {
    Route::middleware('auth:api')->group(function () {

        // event api
        Route::get('event','EventController@index');
        Route::get('event/{id}','EventController@show');
        Route::get('search/{event?}' ,'EventController@search_ko');
        // company api
        Route::resource('company','CompanyController');

        // visitors api
        Route::resource('visitor','VisitorController');
        Route::get('event_visitor/{id}' ,'VisitorController@event_visitor');
        Route::get('remove_event_visitor/{id}' ,'VisitorController@remove_event_visitor');
        Route::post('change_password' ,'VisitorController@change_password');
        
        Route::post('update_profile' ,'VisitorController@update_profile');

        Route::get('add_favorite/{id}' ,'VisitorController@favorites');
        Route::get('favorites' ,'VisitorController@get_favorites');
        Route::get('remove_favorite/{id}' ,'VisitorController@remove_favorites');
        Route::get('attended_events' ,'VisitorController@attended_events');
    });
    Route::get('owner_details' ,'DetailsController@owner');
    Route::post('register' ,'VisitorController@register');
    Route::post('login' ,'VisitorController@login');
    Route::post('logout' ,'VisitorController@logout');

});