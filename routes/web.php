<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\SellApplicationController;
use App\Http\Controllers\BuyApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BoardDecisionController;
use App\Http\Controllers\NoticePublicationController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Shareholders Routes
    Route::resource('shareholders', ShareholderController::class);
    Route::get('shareholders/{id}/data', [ShareholderController::class, 'getShareholderData'])->name('shareholders.data');

    // Sell Applications Routes
    Route::resource('sell-applications', SellApplicationController::class);
    Route::patch('sell-applications/{id}/status', [SellApplicationController::class, 'updateStatus'])->name('sell-applications.update-status');

    // Buy Applications Routes
    Route::resource('buy-applications', BuyApplicationController::class);
    Route::patch('buy-applications/{id}/status', [BuyApplicationController::class, 'updateStatus'])->name('buy-applications.update-status');

    // Documents Routes
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('documents/{id}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('documents/{id}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::post('documents/store', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('documents/{id}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
    Route::delete('documents/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Board Decisions Routes
    Route::resource('board-decisions', BoardDecisionController::class);

    // Notice Publications Routes
    Route::resource('notice-publications', NoticePublicationController::class);

    // Transactions Routes
    Route::resource('transactions', TransactionController::class);
    Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
    Route::post('transactions/{id}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
});
