<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TranslationController;

Route::prefix('translations')->group(function () {
Route::get('/', [TranslationController::class, 'index']); // Search/view all
Route::post('/', [TranslationController::class, 'store']); // Create
Route::get('{id}', [TranslationController::class, 'show']); // View single
Route::put('{id}', [TranslationController::class, 'update']); // Update
Route::get('export/{locale}', [TranslationController::class, 'export']); // JSON export
});
