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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::get('jadwal', 'Api\JadwalController@index');
Route::get('jadwal/{id}', 'Api\JadwalController@show');
Route::post('jadwal', 'Api\JadwalController@store');
Route::put('jadwal/{id}', 'Api\JadwalController@update');
Route::delete('jadwal/{id}', 'Api\JadwalController@destroy');

Route::get('petugas', 'Api\PetugasController@index');
Route::get('petugas/{id}', 'Api\PetugasController@show');
Route::post('petugas', 'Api\PetugasController@store');
Route::put('petugas/{id}', 'Api\PetugasController@update');
Route::delete('petugas/{id}', 'Api\PetugasController@destroy');

Route::get('produk', 'Api\ProdukController@index');
Route::get('produk/{id}', 'Api\ProdukController@show');
Route::post('produk', 'Api\ProdukController@store');
Route::put('produk/{id}', 'Api\ProdukController@update');
Route::delete('produk/{id}', 'Api\ProdukController@destroy');

Route::get('user', 'Api\UserController@index');
Route::get('user/{id}', 'Api\UserController@show');
Route::post('user', 'Api\UserController@store');
Route::put('user/{id}', 'Api\UserController@update');
Route::delete('user/{id}', 'Api\UserController@destroy');
