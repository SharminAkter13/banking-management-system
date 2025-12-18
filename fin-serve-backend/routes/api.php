<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ManagerDashboardController;
use App\Http\Controllers\Dashboard\OfficerDashboardController;
use App\Http\Controllers\Dashboard\TellerDashboardController;
use App\Models\Role;

Route::get('/roles', function () {
    return response()->json([
        'data' => Role::all() // Matches the data structure expected by your Vue code
    ]);
});

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
Route::middleware('auth:sanctum')->group(function () {
    // Customer Routes
    Route::get('/customers', [CustomerController::class, 'index']);    // List customers
    Route::get('/customers/{customer}', [CustomerController::class, 'show']);    // Show single customer
    Route::post('/customers', [CustomerController::class, 'store']);    // Create new customer
    Route::put('/customers/{customer}', [CustomerController::class, 'update']);   // Update customer
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']);   // Delete customer
});

Route::middleware('auth:sanctum')->group(function () {
    // Branch Routes
    Route::get('/branches', [BranchController::class, 'index']);  // List all branches
    Route::get('/branches/{id}', [BranchController::class, 'show']);  // Get a single branch
    Route::post('/branches', [BranchController::class, 'store']);  // Create a new branch
    Route::put('/branches/{id}', [BranchController::class, 'update']);  // Update a branch
    Route::delete('/branches/{id}', [BranchController::class, 'destroy']);  // Delete a branch
});

Route::middleware('auth:sanctum')->group(function () {
   Route::get('/loans', [LoanController::class, 'index'])->middleware('can:viewAny,App\Models\Loan'); // Get all loans
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->middleware('can:view,App\Models\Loan'); // Get a single loan
    Route::post('/loans', [LoanController::class, 'store'])->middleware('can:create,App\Models\Loan'); // Create a new loan
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->middleware('can:update,App\Models\Loan'); // Update loan
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->middleware('can:delete,App\Models\Loan'); // Delete loan
});
