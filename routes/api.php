<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'auth'], function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/users', [UserController::class, 'getUsers']);
    Route::put('/user/status/{id}', [UserController::class, 'editUserStatus']);
});

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('web');
