<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\ProductImageController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//-------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    // users
    Route::apiResource('/users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('throttle:3,1');;
    //------------------------------
    //    Products
    Route::POST('/products', [ProductController::class, 'store'])->name('products.store');
    Route::match(['PUT', 'PATCH'],'/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::DELETE('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    //------------------------------
    //    ProductImages
    Route::POST('/productImages', [ProductImageController::class, 'store'])->name('productImages.store');
    Route::match(['PUT', 'PATCH'],'/productImages/{productImage}', [ProductImageController::class, 'update'])->name('productImages.update');
    Route::DELETE('/productImages/{productImage}', [ProductImageController::class, 'destroy'])->name('productImages.destroy');
    //------------------------------
    //------------------------------
});

//------------------------------
// Users Auth
Route::post('/signup', [AuthController::class, 'signup'])->name('signup')->middleware('throttle:3,1'); // 3 attempts per minute
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('throttle:5,1'); // 5 attempts per minute
//------------------------------
Route::GET('/products', [ProductController::class, 'index'])->name('products.index');
Route::GET('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::GET('/productImages', [ProductImageController::class, 'index'])->name('productImages.index');
Route::GET('/productImages/{productImage}', [ProductImageController::class, 'show'])->name('productImages.show');
//------------------------------








