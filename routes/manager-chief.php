<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlMainPage\controlPage as page;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('branch-manager')->group(function () {
        Route::match(['get', 'post'], '/new-tasks', [page::class, 'default'])
        ->name('branch-manager.new-tasks');

        Route::match(['get', 'post'], '/current-tasks', [page::class, 'default'])
        ->name('branch-manager.current-tasks');
    });

    Route::prefix('office-chief')->group(function () {
        Route::match(['get', 'post'], '/new-tasks', [page::class, 'default'])
        ->name('office-chief.new-tasks');

        Route::match(['get', 'post'], '/current-tasks', [page::class, 'default'])
        ->name('office-chief.current-tasks');
    });
});