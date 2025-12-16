<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ManagerDashboardController;
use App\Http\Controllers\Dashboard\OfficerDashboardController;
use App\Http\Controllers\Dashboard\TellerDashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/roles', [RoleController::class, 'index']);


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);



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

