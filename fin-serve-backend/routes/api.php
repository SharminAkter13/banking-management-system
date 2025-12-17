<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ManagerDashboardController;
use App\Http\Controllers\Dashboard\OfficerDashboardController;
use App\Http\Controllers\Dashboard\TellerDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {
// Get the authenticated user info (me)
    Route::get('/me', [UserController::class, 'me']);
    // Get all users (admin only or other roles with permission)
    Route::get('/users', [UserController::class, 'index'])->middleware('can:view-any,user');

    // Get a single user by id
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('can:view,user');

    // Create a new user (admin only)
    Route::post('/users', [UserController::class, 'store'])->middleware('can:create,user');

    // Update an existing user (admin only or self-update)
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('can:update,user');

    // Delete a user (admin only)
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('can:delete,user');    Route::post('/logout', [AuthController::class, 'logout']);



    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', AdminDashboardController::class);
    });

    Route::middleware('role:branch-manager')->group(function () {
        Route::get('/manager/dashboard', ManagerDashboardController::class);
    });

    Route::middleware('role:loan-officer')->group(function () {
        Route::get('/officer/dashboard', OfficerDashboardController::class);
    });

    Route::middleware('role:bank-teller')->group(function () {
        Route::get('/teller/dashboard', TellerDashboardController::class);
    });

    Route::middleware('role:customer')->group(function () {
        Route::get('/customer/dashboard', CustomerDashboardController::class);
    });
});

