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
    //使用者驗證
    Route::group(['prefix' => 'auth'], function(){
        //Facebook登入
        Route::get('/facebook-sign-in', 'UserController@facebookSignIn');
        //Facebook登入重新導向授權資料處理
        Route::get('/facebook-sign-in-callback', 'UserAuthController@facebookSignInCallbackProcess');
    });
});

Route::post('user/register', 'UserController@store');
Route::post('user/login', 'UserController@login');
Route::put('user/update/{id}', 'UserController@update');

Route::post('owner/register', 'OwnerController@store');
Route::post('owner/login', 'OwnerController@login');
Route::put('owner/update/{id}', 'OwnerController@update');

//all leftover
Route::get('food/all', 'FoodController@index');

Route::get('test', 'UserController@test');


