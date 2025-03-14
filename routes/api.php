<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function () {
    return 'API';
});

Route::prefix('v1/admin')->group(function () {

    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');
});

// super admin routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:super_admin', 'auth:sanctum'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    });
});


// product manager routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:product_manager|super_admin', 'auth:sanctum'])->group(function () {
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
    });
});

// user manager routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:user_manager|super_admin', 'auth:sanctum'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
    });
});
