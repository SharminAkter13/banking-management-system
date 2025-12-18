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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:sanctum')->group(function () {

    // Authenticated user info
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users CRUD (with policies)
    Route::get('/users', [UserController::class, 'index'])->middleware('can:viewAny,App\Models\User');
    Route::get('/users/{user}', [UserController::class, 'show'])->middleware('can:view,user');
    Route::post('/users', [UserController::class, 'store'])->middleware('can:create,App\Models\User');
    Route::put('/users/{user}', [UserController::class, 'update'])->middleware('can:update,user');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware('can:delete,user');

    // Role-specific dashboards
    Route::middleware('role:admin')->get('/admin/dashboard', AdminDashboardController::class);
    Route::middleware('role:branch-manager')->get('/manager/dashboard', ManagerDashboardController::class);
    Route::middleware('role:loan-officer')->get('/officer/dashboard', OfficerDashboardController::class);
    Route::middleware('role:bank-teller')->get('/teller/dashboard', TellerDashboardController::class);
    Route::middleware('role:customer')->get('/customer/dashboard', CustomerDashboardController::class);

});
