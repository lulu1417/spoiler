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
    Route::post('/facebook/register', 'FBController@register');
    Route::post('/facebook/login', 'FBController@login');

});

Route::group(['prefix' => 'owner'], function(){
    Route::post('/register', 'OwnerController@store');
    Route::post('/login', 'OwnerController@login');
    Route::post('/logout', 'OwnerController@logout');
    Route::put('update/{id}', 'OwnerController@update');
});

Route::group(['prefix' => 'restaurant'], function(){
    Route::post('/create', 'RestaurantController@store');
    Route::put('update/{id}', 'RestaurantController@update');
    Route::post('/search', 'RestaurantController@search');
    Route::get('/all', 'RestaurantController@index');

});

Route::group(['prefix' => 'food'], function(){
    Route::post('create', 'FoodController@store');
    Route::put('update/{id}', 'FoodController@update');
    Route::post('search', 'FoodController@search');
    Route::get('all', 'FoodController@index');

});

Route::group(['prefix' => 'order'], function(){
    Route::post('create', 'OrderController@store');
    Route::post('search', 'OrderController@search');
    Route::put('complete/{id}', 'OrderController@complete');
    Route::delete('cancel/{id}', 'OrderController@destroy');
    Route::get('look/{id}', 'OrderController@index');
});

Route::group(['prefix' => 'subscript'], function(){
    Route::post('create', 'SubscriptController@store');
    Route::post('search', 'SunscriptController@search');
    Route::get('notice', 'SubscriptController@notice');
    Route::delete('cancel/{id}', 'SubscriptController@destroy');
});



//Route::post('user/register', 'UserController@store');
//Route::post('user/login', 'UserController@login');
//Route::post('user/logout', 'UserController@logout');
//Route::put('user/update/{id}', 'UserController@update');
//facebook login
//Route::post('user/login/facebook', 'FBController@getFacebookResources');

//owner
//Route::post('owner/register', 'OwnerController@store');
//Route::post('owner/login', 'OwnerController@login');
//Route::post('owner/logout', 'OwnerController@logout');
//Route::put('owner/update/{id}', 'OwnerController@update');

//restaurant
//Route::post('restaurant/create', 'RestaurantController@store');
//Route::put('restaurant/update', 'RestaurantController@update');
//Route::post('restaurant/search', 'RestaurantController@search');
//Route::get('restaurant/all', 'RestaurantController@index');

//food
//Route::post('food/create', 'FoodController@store');
//Route::put('food/update', 'FoodController@update');
//Route::post('food/search', 'FoodController@search');
//Route::get('food/all', 'FoodController@index');




