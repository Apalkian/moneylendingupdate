<?php

use App\Http\Controllers\AdditionalCapitalController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    if (session()->has('admin_id') || (Auth::check() && Auth::user()?->isAdmin())) {
        return redirect()->route('dashboard');
    }

    if (Auth::check() && Auth::user()?->isBorrower()) {
        return redirect()->route('borrower.portal');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/borrower-portal', [WebController::class, 'borrowerPortal'])->name('borrower.portal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin.auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [WebController::class, 'index'])->name('dashboard');
    Route::get('/ledger', [WebController::class, 'ledger'])->name('ledger');

    Route::resource('borrowers', BorrowerController::class);
    Route::resource('loans', LoanController::class);

    Route::get('/payments/create', [WebController::class, 'createPayment'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/additional-capital/create', [AdditionalCapitalController::class, 'create'])->name('additional-capital.create');
    Route::post('/additional-capital', [AdditionalCapitalController::class, 'store'])->name('additional-capital.store');

    Route::get('/report-loans', [ReportController::class, 'getLoanReport'])->name('report.loans');
    Route::get('/report-payments', [ReportController::class, 'getPaymentHistory'])->name('report.payments');
    Route::get('/report-balances', [ReportController::class, 'getOutstandingBalances'])->name('report.balances');
});
