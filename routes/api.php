<?php

use App\Events\FoodAdded;
use http\Client\Response;
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
    Route::get('facebook/call-back', 'FBController@fbCallback');
    Route::get('look/{id}', 'UserController@look');
    Route::get('subscript', 'UserController@getSubscription');
    Route::get('', 'UserController@all');
    Route::post('report', 'UserController@reportUser');

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
    Route::middleware('auth:owner_api')->patch('{id}', 'RestaurantController@update');
    Route::get('', 'RestaurantController@all');
    Route::post('filter', 'RestaurantController@filter');
    Route::get('{id}', 'RestaurantController@look');
    Route::middleware('auth:api')->post('score', 'RestaurantController@score');
    Route::get('subscript', 'RestaurantController@getSubscriptUsers');

});

Route::group(['prefix' => 'food'], function(){
    Route::middleware('auth:owner_api')->post('', 'FoodController@store')->name('food');
    Route::middleware('auth:owner_api')->patch('/{id}', 'FoodController@update');
    Route::get('', 'FoodController@index');
    Route::get('{id}', 'FoodController@look');
});

Route::group(['prefix' => 'order'], function(){
    Route::middleware('auth:api')->post('', 'OrderController@store');
    Route::middleware('auth:api')->put('{id}', 'OrderController@complete');
    Route::middleware('auth:api')->delete('{id}', 'OrderController@cancel');
    Route::get('look/{id}', 'OrderController@look');
    Route::get('', 'OrderController@index');

});

Route::group(['prefix' => 'subscript'], function(){
    Route::post('', 'SubscriptController@store');
    Route::post('search', 'SubscriptController@search');
    Route::get('notice', 'SubscriptController@notice');
    Route::delete('{id}', 'SubscriptController@cancel');
});

Route::post('search', 'FoodController@search');
Route::get('redirect', 'FBController@redirect');

Route::group(['prefix' => 'report'], function(){
    Route::post('', 'ReportController@report');

});








