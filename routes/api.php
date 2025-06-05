<?php

use App\Http\Controllers\Api\TranslationExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;

Route::prefix('translations')
    ->middleware(['auth:token'])
    ->group(function () {
        Route::get('/', [TranslationController::class, 'index']); // Search/view all
        Route::post('/', [TranslationController::class, 'store']); // Create
        Route::get('{id}', [TranslationController::class, 'show']); // View single
        Route::put('{id}', [TranslationController::class, 'update']); // Update
        Route::get('export/{locale}', [TranslationExportController::class, 'export']); // JSON export
    });
