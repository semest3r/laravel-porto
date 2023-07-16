<?php

use App\Http\Controllers\Auditrail\AuditrailController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Category\GroupCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Subscriber\SubscriberController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('', [UserController::class, 'getUsers']);
        Route::put('/status/{id}', [UserController::class, 'editUserStatus']);
    });

    Route::group(['prefix' => 'categories'], function(){
        Route::get('', [CategoryController::class, 'getCategories']);
        Route::get('/{id}', [CategoryController::class, 'getCategory']);
        Route::post('/create', [CategoryController::class, 'create']);
        Route::put('/edit/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
    });

    Route::group(['prefix' => 'group-categories'], function(){
        Route::get('', [GroupCategoryController::class, 'getGroupCategories']);
        Route::get('/{id}', [GroupCategoryController::class, 'getGroupCategory']);
        Route::post('/create', [GroupCategoryController::class, 'create']);
        Route::put('/edit/{id}', [GroupCategoryController::class, 'update']);
        Route::delete('/delete/{id}', [GroupCategoryController::class, 'delete']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('', [ProductController::class, 'getProducts']);
        Route::get('/{id}', [ProductController::class, 'getProduct']);
        Route::post('/create', [ProductController::class, 'create']);
        Route::put('/edit/{id}', [ProductController::class, 'update']);
        Route::deletE('/delete/{id}', [ProductController::class, 'delete']);
    });

    Route::group(['prefix' => 'product-img'], function () {
        Route::post('/create/{id}', [ProductController::class, 'addImg']);
        Route::put('/edit/{id}', [ProductController::class, 'updateImg']);
    });

    Route::group(['prefix' => 'subscribers'], function () {
        Route::get('', [SubscriberController::class, 'getSubscribers']);
        Route::get('/{id}', [SubscriberController::class, 'getSubscriber']);
        Route::post('/create', [SubscriberController::class, 'create']);
        Route::put('/edit/{id}', [SubscriberController::class, 'update']);
        Route::delete('/delete/{id}', [SubscriberController::class, 'delete']);
        Route::put('/edit-status/{id}', [SubscriberController::class, 'editSubscriberStatus']);
    });

    Route::get('/auditrails', [AuditrailController::class, 'getAuditrails']);
});

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('web');
