<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', "App\Http\Controllers\Api\AuthController@login")->name("login");
    Route::post('/register', "App\Http\Controllers\Api\AuthController@register")->name("register");
    Route::post('/logout', "App\Http\Controllers\Api\AuthController@logout")->name("logout");
    Route::post('/refresh', "App\Http\Controllers\Api\AuthController@refresh")->name("refresh");
    Route::get('/user-profile', "App\Http\Controllers\Api\AuthController@userProfile")->name("userProfile");
});
Route::resource('products', 'App\Http\Controllers\ProductController');
Route::put('products/update-product/{product}', 'App\Http\Controllers\ProductController@apiUpdate');

