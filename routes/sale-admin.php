<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlAssignTask as assignTask;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlDupView\dupListOfTasks;
use App\Http\Controllers\controlDupView\dupDetailMsg;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('sale-admin')->group(function () {
        Route::get('/new-tasks', [dupListOfTasks::class, 'newTasks'])
        ->name('sale-admin.new-tasks');

        Route::prefix('current-tasks')->group(function () {
            Route::get('/', [dupListOfTasks::class, 'currentTasks'])
            ->name('sale-admin.current-tasks');

            Route::post('/detail-message', [dupDetailMsg::class, 'detail'])
            ->name('sale-admin.detail-message');

            Route::post('/assign-task', [assignTask::class, 'wilAssign'])
            ->name('sale-admin.assign-task');

            Route::post('/assign', [assignTask::class, 'assignTask'])
            ->name('sale-admin.confirm-assign');
        });

        Route::prefix('credit-debit')->group(function () {
            Route::get('/', [authen::class, 'resetPopupValidate'])
            ->name('sale-admin.credit-debit');

            Route::post('/detail-credit-debit', [authen::class, 'resetPopupValidate'])
            ->name('sale-admin.detail-credit-debit');
        });
    });
});