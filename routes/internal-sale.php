<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('internal-sale')->group(function () {
        Route::get('/new-tasks', function () {
            return view('listOfTasks');
        })->name('internal-sale.new-tasks');

        Route::prefix('current-tasks')->group(function () {
            Route::get('/', [dupFormAuthen::class, 'editTasks'])
            ->name('internal-sale.current-tasks');

            Route::post('/detail-message', [dupFormAuthen::class, 'editTasks'])
            ->name('internal-sale.detail-message');

            Route::post('/assign-task', [dupFormAuthen::class, 'editTasks'])
            ->name('internal-sale.assign-task');
        });

        Route::prefix('credit-debit')->group(function () {
            Route::get('/', [authen::class, 'resetPopupValidate'])
            ->name('internal-sale.credit-debit');

            Route::post('/detail-credit-debit', [authen::class, 'resetPopupValidate'])
            ->name('internal-sale.detail-credit-debit');
        });
    });
});