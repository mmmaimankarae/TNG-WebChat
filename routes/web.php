<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\controlForm\controlFormAuthen;
use App\Http\Controllers\MessageController;

/* authentication */
Route::get('/', [controlFormAuthen::class, 'signin']);

Route::prefix('authen')->group(function () {
    Route::post('/', [AuthenController::class, 'authValidate']);
    Route::post('/reset-forgot', [AuthenController::class, 'forgotPopupValidate']);
    Route::post('/reset', [AuthenController::class, 'resetPopupValidate']);
});

Route::get('/messages', [MessageController::class, 'index']);
Route::get('/messages/download/{messageId}', [MessageController::class, 'downloadImage'])->name('download.image');
Route::get('/messages/show/{messageId}', [MessageController::class, 'showImage'])->name('show.image');
Route::get('/welcome', function () {
    return view('welcome');
});