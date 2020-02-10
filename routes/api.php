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

Route::group(['prefix' => 'user'], function(){
    Route::post('/register', 'UserController@store');
    Route::post('/login', 'UserController@login');
    Route::post('/logout', 'UserController@logout');
    Route::put('update/{id}', 'UserController@update');
    //facebook login
    Route::post('/facebook/login', 'FBController@login');


});

Route::group(['prefix' => 'owner'], function(){
    Route::post('/register', 'OwnerController@store');
    Route::post('/login', 'OwnerController@login');
    Route::post('/logout', 'OwnerController@logout');
    Route::put('update/{id}', 'OwnerController@update');
});

Route::group(['prefix' => 'restaurant'], function(){
    Route::middleware('auth:admin_api')->post('/create', 'RestaurantController@store');
    Route::put('update/{id}', 'RestaurantController@update');

    Route::post('/search', 'RestaurantController@search');
    Route::get('/all', 'RestaurantController@index');
    Route::get('look/{id}', 'RestaurantController@look');
    Route::post('scoreUser', 'RestaurantController@scoreUser');

});

Route::group(['prefix' => 'food'], function(){
    Route::post('create', 'FoodController@store');
    Route::put('update/{id}', 'FoodController@update');

    Route::post('search', 'FoodController@search');
    Route::get('all', 'FoodController@index');
    Route::get('look/{id}', 'FoodController@look');

});

Route::group(['prefix' => 'order'], function(){
    Route::post('create', 'OrderController@store');

    Route::post('search', 'OrderController@search');

    Route::put('complete/{id}', 'OrderController@complete');
    Route::delete('cancel/{id}', 'OrderController@destroy');
    Route::get('look/{id}', 'OrderController@look');
});

Route::group(['prefix' => 'subscript'], function(){
    Route::post('create', 'SubscriptController@store');
    Route::post('search', 'SunscriptController@search');
    Route::get('notice', 'SubscriptController@notice');
    Route::delete('cancel/{id}', 'SubscriptController@destroy');
});






