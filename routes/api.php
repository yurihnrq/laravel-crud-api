<?php

// use Illuminate\Http\Request;
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

// Aqui defino os endpoints da API e o método suportado utilizando
// as funções estáticas da classe Route.
// Além do endpoint, informo a função que será executada quando 
// uma requisição acontecer.
Route::get('user', 'App\Http\Controllers\UserController@getAllUsers');
Route::get('user/{id}', 'App\Http\Controllers\UserController@getUser');
Route::get('user/info/{info}', 'App\Http\Controllers\UserController@getUsersByInfo');
Route::post('user', 'App\Http\Controllers\UserController@createUser');
Route::put('user/{id}', 'App\Http\Controllers\UserController@updateUser');
Route::delete('user/{id}', 'App\Http\Controllers\UserController@deleteUser');
