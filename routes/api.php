<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function () {
    return 'API';
});

Route::prefix('v1/admin')->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// super admin routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:super_admin', 'auth:sanctum'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::apiResource('categories',CategoryController::class);
    });
});


// product manager routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:product_manager', 'auth:sanctum'])->group(function () {
        Route::apiResource('products',ProductController::class);
    });
});

// user manager routes
Route::middleware(['role:user_manager', 'auth:sanctum'])->group(function () {
    //    
});
