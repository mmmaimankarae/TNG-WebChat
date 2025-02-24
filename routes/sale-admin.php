<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlAssignTask as assignTask;
use App\Http\Controllers\controlPage as page;
use App\Http\Controllers\controlDupView\dupFormAddData as addData;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('sale-admin')->group(function () {
        Route::match(['get', 'post'], '/new-tasks', [page::class, 'default'])
        ->name('sale-admin.new-tasks');

        Route::match(['get', 'post'], '/current-tasks', [page::class, 'default'])
        ->name('sale-admin.current-tasks');

        Route::post('/current-tasks/assign-task', [assignTask::class, 'wilAssign'])
        ->name('sale-admin.assign-task');

        Route::post('/current-tasks/assign', [assignTask::class, 'assignTask'])
        ->name('sale-admin.confirm-assign');

        Route::match(['get', 'post'], '/payment', [addData::class, 'default'])
        ->name('sale-admin.payment');
    });
});