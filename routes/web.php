<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;

/* authentication เมื่อยังไม่ได้ signin */
Route::get('/', [dupFormAuthen::class, 'signin']);

Route::prefix('authen')->group(function () {
    Route::post('/', [authen::class, 'authValidate']);
    Route::post('/reset-forgot', [authen::class, 'forgotPopupValidate']);
});

Route::middleware(['access.jwt'])->group(function () {
    Route::prefix('sale-admin')->group(function () {
        Route::get('/new-tasks', function () {
            return view('listOfTasks');
        })->name('sale-admin.new-tasks');

        Route::prefix('current-tasks')->group(function () {
            Route::get('/', [dupFormAuthen::class, 'editTasks'])
            ->name('sale-admin.current-tasks');

            Route::post('/detail-message', [dupFormAuthen::class, 'editTasks'])
            ->name('sale-admin.detail-message');

            Route::post('/assign-task', [dupFormAuthen::class, 'editTasks'])
            ->name('sale-admin.assign-task');
        });

        Route::prefix('credit-debit')->group(function () {
            Route::get('/', [authen::class, 'resetPopupValidate'])
            ->name('sale-admin.credit-debit');

            Route::post('/detail-credit-debit', [authen::class, 'resetPopupValidate'])
            ->name('sale-admin.detail-credit-debit');
        });

    });

    // Route::prefix('internal-sale')->group(function () {

    // });

    // Route::prefix('branch-manager')->group(function () {

    // });

    // Route::prefix('office-chief')->group(function () {

    // });

    Route::prefix('authen')->group(function () {
        Route::get('/reset', [dupFormAuthen::class, 'reset']);
        Route::post('/reset-password', [authen::class, 'resetPopupValidate']);
    });

    Route::get('/signout', [authen::class, 'authenSignout']);
});


