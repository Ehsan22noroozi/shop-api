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

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::put('/products/{product}', [ProductController::class, 'update']);

Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/items', [CartController::class, 'storeItem']);
Route::delete('/products/{product}', [ProductController::class, 'destroy']);
Route::patch('/products/{product}/restore', [ProductController::class, 'restore']);

Route::post('/products/{product}/images', [ProductImageController::class, 'store']);
