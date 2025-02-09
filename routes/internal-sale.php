<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlPage as page;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('internal-sale')->group(function () {
        Route::match(['get', 'post'], '/new-tasks', [page::class, 'default'])
        ->name('internal-sale.new-tasks');

        Route::match(['get', 'post'], '/current-tasks', [page::class, 'default'])
        ->name('internal-sale.current-tasks');
    });
});