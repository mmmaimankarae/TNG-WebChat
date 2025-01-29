<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlDupView\dupListOfTasks;
use App\Http\Controllers\controlDupView\dupDetailMsg;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('internal-sale')->group(function () {
        Route::get('/new-tasks', [dupListOfTasks::class, 'newTasks'])
        ->name('internal-sale.new-tasks');

        Route::prefix('current-tasks')->group(function () {
            Route::get('/', [dupListOfTasks::class, 'currentTasks'])
            ->name('internal-sale.current-tasks');

            Route::post('/detail-message', [dupListOfTasks::class, 'detail'])
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