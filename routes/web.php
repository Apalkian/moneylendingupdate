<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdditionalCapitalController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\AdminController;

// --- Public Routes ---
Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login']);
Route::get('/logout', [AdminController::class, 'logout']);
Route::get('/admin/register', [AdminController::class, 'create']);
Route::post('/admin/register', [AdminController::class, 'store']);

// --- Protected Routes (Require Admin Login) ---
Route::middleware(['admin.auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [WebController::class, 'index'])->name('dashboard');

    // Borrowers
   // Replace your individual borrower routes with this one line:
Route::resource('borrowers', BorrowerController::class);

    // Loans
    Route::resource('loans', LoanController::class);

    // Payments
    Route::get('/payments/create', [WebController::class, 'createPayment']);
    Route::post('/payments', [PaymentController::class, 'store']);

    // Additional Capital
    Route::get('/additional-capital/create', [AdditionalCapitalController::class, 'create']);
    Route::post('/additional-capital', [AdditionalCapitalController::class, 'store']);

    // JSON Reports
    Route::get('/report-loans', [ReportController::class, 'getLoanReport']);
    Route::get('/report-payments', [ReportController::class, 'getPaymentHistory']);
    Route::get('/report-balances', [ReportController::class, 'getOutstandingBalances']);
});