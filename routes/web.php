<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\controlForm\controlFormAuthen;

/* authentication */
Route::get('/', [controlFormAuthen::class, 'signin']);

Route::prefix('authen')->group(function () {
    Route::post('/', [AuthenController::class, 'authValidate']);
    Route::post('/reset-forgot', [AuthenController::class, 'forgotPopupValidate']);
    Route::post('/reset', [AuthenController::class, 'resetPopupValidate']);
});


Route::get('/welcome', function () {
    return view('welcome');
});