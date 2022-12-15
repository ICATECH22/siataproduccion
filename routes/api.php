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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/sivycMovil/login', 'App\Http\Controllers\TokenMovil@login');
Route::post('/siata/notificaciones', ['as' => 'getNotificaciones', 'uses' => 'App\Http\Controllers\TokenMovil@getNotificaciones']);
Route::post('/siata/updateRead', ['as' => 'leerNotificacion', 'uses' => 'App\Http\Controllers\TokenMovil@updateRead']);
Route::post('/siata/logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\TokenMovil@updateToken']);