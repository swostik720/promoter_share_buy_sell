<?php

use Illuminate\Support\Facades\Route;
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
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Shareholders
Route::resource('shareholders', ShareholderController::class);

// Sell Applications
Route::resource('sell-applications', SellApplicationController::class);
Route::patch('sell-applications/{id}/status', [SellApplicationController::class, 'updateStatus'])->name('sell-applications.update-status');

// Buy Applications
Route::resource('buy-applications', BuyApplicationController::class);
Route::patch('buy-applications/{id}/status', [BuyApplicationController::class, 'updateStatus'])->name('buy-applications.update-status');

// Documents
Route::resource('documents', DocumentController::class);
Route::get('documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');
Route::patch('documents/{id}/verify', [DocumentController::class, 'verify'])->name('documents.verify');

// Board Decisions
Route::resource('board-decisions', BoardDecisionController::class);

// Notice Publications
Route::resource('notice-publications', NoticePublicationController::class);

// Transactions
Route::resource('transactions', TransactionController::class);
Route::patch('transactions/{id}/complete', [TransactionController::class, 'complete'])->name('transactions.complete');
Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
