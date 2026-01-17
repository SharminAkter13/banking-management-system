<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Core API Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\KYCFormController;

// Banking API Controllers
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\AccountTypeController;
use App\Http\Controllers\Api\AccountStatusController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\InstallmentController;
use App\Http\Controllers\Api\InstallmentPaymentController;
use App\Http\Controllers\Api\LoanPaymentController;
use App\Http\Controllers\Api\LoanTypeController;


// Workflow API Controllers
use App\Http\Controllers\Api\ApprovalController;
use App\Http\Controllers\Api\ApprovalStepController;
use App\Http\Controllers\Api\ApprovalActionController;

// Dashboard Controllers (Outside Api Folder)
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\CustomerDashboardController;
use App\Http\Controllers\Dashboard\ManagerDashboardController;
use App\Http\Controllers\Dashboard\OfficerDashboardController;
use App\Http\Controllers\Dashboard\TellerDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/roles', [RoleController::class, 'index']); // Public role listing for registration

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // User Profile & Authentication
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 1. Core Resources
    Route::apiResource('users', UserController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('kyc-forms', KYCFormController::class); // Matches kyc_forms table

    // 2. Banking Resources
    Route::apiResource('accounts', AccountController::class); // Matches accounts table
    Route::apiResource('transactions', TransactionController::class); // Matches transactions table
    Route::apiResource('account-types', AccountTypeController::class);
    Route::apiResource('account-statuses', AccountStatusController::class);

    // 3. Loan Management
    Route::apiResource('loans', LoanController::class); // Matches loans table
    Route::apiResource('installments', InstallmentController::class)->only(['index','show']);

    Route::post('installments/pay', [InstallmentPaymentController::class,'pay']);
    Route::apiResource('loan-payments', LoanPaymentController::class); // Matches loan_payments table
    Route::apiResource('loan-types', LoanTypeController::class);


    // 4. Approval Workflow System
    Route::apiResource('approvals', ApprovalController::class); // Matches approvals table
    Route::apiResource('approval-steps', ApprovalStepController::class); // Matches approval_steps table
    Route::apiResource('approval-actions', ApprovalActionController::class); // Matches approval_actions table

    /*
    |----------------------------------------------------------------------
    | Role-Based Dashboard Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->get('/admin/dashboard', AdminDashboardController::class);
    Route::middleware('role:branch-manager')->get('/manager/dashboard', ManagerDashboardController::class);
    Route::middleware('role:loan-officer')->get('/officer/dashboard', OfficerDashboardController::class);
    Route::middleware('role:bank-teller')->get('/teller/dashboard', TellerDashboardController::class);
    Route::middleware('role:customer')->get('/customer/dashboard', CustomerDashboardController::class);
});