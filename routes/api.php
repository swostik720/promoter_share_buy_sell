<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShareholderController;
use App\Http\Controllers\SellApplicationController;
use App\Http\Controllers\BuyApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BoardDecisionController;
use App\Http\Controllers\NoticePublicationController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Shareholders
Route::prefix('shareholders')->group(function () {
    Route::get('/', [ShareholderController::class, 'index']);
    Route::post('/', [ShareholderController::class, 'store']);
    Route::get('/promoters', [ShareholderController::class, 'getPromoters']);
    Route::get('/{id}', [ShareholderController::class, 'show']);
    Route::put('/{id}', [ShareholderController::class, 'update']);
});

// Sell Applications
Route::prefix('sell-applications')->group(function () {
    Route::get('/', [SellApplicationController::class, 'index']);
    Route::post('/', [SellApplicationController::class, 'store']);
    Route::get('/{id}', [SellApplicationController::class, 'show']);
    Route::patch('/{id}/status', [SellApplicationController::class, 'updateStatus']);
});

// Buy Applications
Route::prefix('buy-applications')->group(function () {
    Route::get('/', [BuyApplicationController::class, 'index']);
    Route::post('/', [BuyApplicationController::class, 'store']);
    Route::get('/{id}', [BuyApplicationController::class, 'show']);
    Route::patch('/{id}/status', [BuyApplicationController::class, 'updateStatus']);
    Route::get('/sell-application/{sellApplicationId}', [BuyApplicationController::class, 'getBySellApplication']);
});

// Documents
Route::prefix('documents')->group(function () {
    Route::post('/', [DocumentController::class, 'store']);
    Route::get('/{id}', [DocumentController::class, 'show']);
    Route::get('/{id}/download', [DocumentController::class, 'download']);
    Route::patch('/{id}/verify', [DocumentController::class, 'verify']);
    Route::get('/entity/list', [DocumentController::class, 'getByEntity']);
});

// Board Decisions
Route::prefix('board-decisions')->group(function () {
    Route::post('/', [BoardDecisionController::class, 'store']);
    Route::get('/{id}', [BoardDecisionController::class, 'show']);
    Route::put('/{id}', [BoardDecisionController::class, 'update']);
});

// Notice Publications
Route::prefix('notice-publications')->group(function () {
    Route::post('/', [NoticePublicationController::class, 'store']);
    Route::get('/{id}', [NoticePublicationController::class, 'show']);
    Route::put('/{id}', [NoticePublicationController::class, 'update']);
});

// Transactions
Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::get('/{id}', [TransactionController::class, 'show']);
    Route::patch('/{id}/complete', [TransactionController::class, 'complete']);
    Route::patch('/{id}/status', [TransactionController::class, 'updateStatus']);
});
