<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('register', "AuthController@register")->name('register');
Route::post('login', "AuthController@login")->name('login');

Route::get('posts', "PostsController@index");
Route::get('post/{id}', "PostsController@show");

Route::middleware('auth:api')->group(function () {
    Route::post('posts', "PostsController@store");
    Route::put('post/{id}', "PostsController@update");
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
