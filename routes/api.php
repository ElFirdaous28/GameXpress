<?php

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\DashboardController;
use App\Http\Controllers\Api\V1\Admin\ProductController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
        Route::delete('products/{product}/force-destroy', [ProductController::class, 'forceDestroy'])->name('products.forcs-destroy');
        Route::apiResource('categories', CategoryController::class);
    });
});

// user manager routes
Route::prefix('v1/admin')->group(function () {
    Route::middleware(['role:user_manager|super_admin', 'auth:sanctum'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::put('/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::put('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    });
});


// Store session (POST /set-session)
Route::post('/set-session', function (Request $request) {
    session(['user_id' => $request->input('user_id')]); // Store user_id
    session()->save(); // **Force session to be saved**
    
    return response()->json([
        'message' => 'Session stored successfully',
        'session_id' => session()->getId(), // Return session ID
    ]);
})->middleware('session');

// Retrieve session (GET /get-session)
Route::get('/get-session', function (Request $request) {
    return response()->json([
        'user_id' => session('user_id'), // Retrieve user_id from session
        'session_id' => session()->getId(),
    ]);
})->middleware('session');