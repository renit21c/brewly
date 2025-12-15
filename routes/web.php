<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

        // Report routes
        Route::prefix('reports')->group(function () {
            Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
            Route::get('/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
            Route::get('/analytics', [ReportController::class, 'analytics'])->name('reports.analytics');
        });

        // User routes
        Route::prefix('users')->group(function () {
                        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
                        Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/store', [UserController::class, 'store'])->name('users.store');
            Route::get('/activity', [UserController::class, 'activity'])->name('users.activity');
        });

        // Setting routes
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('settings.index');
            Route::post('/', [SettingController::class, 'update'])->name('settings.update');
        });
    });

    // Cashier routes
    Route::middleware('role:cashier')->prefix('pos')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('pos.index');
        Route::post('/checkout', [TransactionController::class, 'store'])->name('pos.checkout');
        Route::post('/void-item', [TransactionController::class, 'voidItem'])->name('pos.void-item');
    });

    // Shift routes (for all authenticated users)
    Route::prefix('shift')->group(function () {
        Route::get('/open', [ShiftController::class, 'openForm'])->name('shift.open');
        Route::post('/open', [ShiftController::class, 'open'])->name('shift.open.store');
        Route::get('/close', [ShiftController::class, 'closeForm'])->name('shift.close');
        Route::post('/close', [ShiftController::class, 'close'])->name('shift.close.store');
    });
});
