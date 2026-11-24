<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard if logged in, otherwise to login page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi Laundry
    Route::resource('transactions', TransactionController::class);
    Route::patch('/transactions/{transaction}/quick-status', [TransactionController::class, 'quickStatus'])->name('transactions.quick-status');
    Route::patch('/transactions/{transaction}/quick-payment', [TransactionController::class, 'quickPayment'])->name('transactions.quick-payment');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'printReceipt'])->name('transactions.receipt');

    // Pelanggan (Customers)
    Route::resource('customers', CustomerController::class);

    // Data Layanan (Packages) - Viewable by all auth users, managed by Admin
    Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
    Route::middleware('admin')->group(function () {
        Route::get('/packages/create', [PackageController::class, 'create'])->name('packages.create');
        Route::post('/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/packages/{package}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::put('/packages/{package}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/packages/{package}', [PackageController::class, 'destroy'])->name('packages.destroy');
    });

    // Laporan Keuangan
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
