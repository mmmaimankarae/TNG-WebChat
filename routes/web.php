<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;

/* authentication */
Route::get('/', [dupFormAuthen::class, 'signin']);

Route::prefix('authen')->group(function () {
    Route::post('/', [authen::class, 'authValidate']);
    Route::post('/reset-forgot', [authen::class, 'forgotPopupValidate']);
    Route::post('/reset', [authen::class, 'resetPopupValidate']);
});


Route::get('/welcome', function () {
    return view('welcome');
});