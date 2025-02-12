<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlDupView\dupFormAddData as addData;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('it-support')->group(function () {
        Route::match(['get', 'post'], '/employee', [addData::class, 'default'])
        ->name('it-support.employee');

        Route::match(['get', 'post'], '/product', [addData::class, 'default'])
        ->name('it-support.product');

        Route::match(['get', 'post'], '/branch', [addData::class, 'default'])
        ->name('it-support.branch');
    });
});