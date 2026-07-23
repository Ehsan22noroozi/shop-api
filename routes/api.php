<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductImageController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'storeItem']);


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('permission:product.create');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('permission:product.update');

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('permission:product.delete');

    Route::patch('/products/{product}/restore', [ProductController::class, 'restore'])
        ->middleware('permission:product.restore');


    Route::post('/products/{product}/images', [ProductImageController::class, 'store'])
        ->middleware('permission:product.update');

    Route::delete('/products/{product}/images/{image}', [ProductImageController::class, 'destroy'])
        ->middleware('permission:product.update');
});
