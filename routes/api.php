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

Route::get('states/{country_id}', [App\Http\Controllers\APIController::class, 'states']);
Route::get('user/{id}', [App\Http\Controllers\APIController::class, 'user']);
Route::get('users', [App\Http\Controllers\APIController::class, 'users']);
