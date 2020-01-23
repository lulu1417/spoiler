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


Route::post('user/register', 'UserController@store');
Route::post('user/login', 'UserController@login');
Route::put('user/update/{id}', 'UserController@update');

Route::post('owner/register', 'OwnerController@store');
Route::post('owner/login', 'OwnerController@login');
Route::put('owner/update/{id}', 'OwnerController@update');

Route::get('/update', 'OwnerController@update');


