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
    Route::post('register', 'UserController@store');
    Route::post('login', 'UserController@login');
    Route::put('logout/{id}', 'UserController@logout');
    Route::put('update/{id}', 'UserController@update');
    //facebook login
    Route::post('facebook/login', 'FBController@login');
    Route::get('look/{id}', 'UserController@look');
    Route::get('subscript', 'UserController@getSubscription');
    Route::get('', 'UserController@all');


});

Route::group(['prefix' => 'owner'], function(){
    Route::post('/register', 'OwnerController@store');
    Route::post('/login', 'OwnerController@login');
    Route::post('/logout', 'OwnerController@logout');
    Route::put('update/{id}', 'OwnerController@update');
    Route::get('', 'OwnerController@all');
});

Route::group(['prefix' => 'restaurant'], function(){
    Route::middleware('auth:owner_api')->post('', 'RestaurantController@store');
    Route::middleware('auth:owner_api')->put('/{id}', 'RestaurantController@update');
    Route::post('distance', 'RestaurantController@distanceCalculate');
    Route::get('', 'RestaurantController@index');
    Route::get('look/{id}', 'RestaurantController@look');
    Route::post('scoreUser', 'RestaurantController@scoreUser');
    Route::get('subscript', 'RestaurantController@getSubscriptUsers');

});

Route::group(['prefix' => 'food'], function(){
//    Route::middleware('auth:owner_api')->put('/{id}', 'FoodController@update');
//    Route::get('', 'FoodController@index');
//    Route::get('look/{id}', 'FoodController@look');
    Route::post('', 'FoodController@store');

});

Route::group(['prefix' => 'order'], function(){
    Route::middleware('auth:api')->post('', 'OrderController@store');
    Route::post('search', 'OrderController@search');

    Route::middleware('auth:api')->put('complete/{id}', 'OrderController@complete');
    Route::middleware('auth:api')->delete('cancel/{id}', 'OrderController@cancel');
    Route::get('look/{id}', 'OrderController@look');
});

Route::group(['prefix' => 'subscript'], function(){
    Route::post('', 'SubscriptController@store');
    Route::post('search', 'SunscriptController@search');
    Route::get('notice', 'SubscriptController@notice');
    Route::delete('cancel/{id}', 'SubscriptController@destroy');
});

Route::post('search', 'FoodController@search');






