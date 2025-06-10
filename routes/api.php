<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//-------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    // users
    Route::apiResource('/users', UserController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
    //------------------------------
    //    Products
    Route::POST('/products', [ProductController::class, 'store'])->name('products.store');
    Route::match(['PUT', 'PATCH'],'/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::DELETE('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    //------------------------------
});

//------------------------------
// Users Auth
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/login', [AuthController::class, 'login'])->name('login');
//------------------------------
Route::GET('/products', [ProductController::class, 'index'])->name('products.index');
Route::GET('/products/{product}', [ProductController::class, 'show'])->name('products.show');
//------------------------------








