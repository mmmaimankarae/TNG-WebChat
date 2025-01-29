<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlDupView\dupListOfTasks;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('branch-manager')->group(function () {
        Route::get('/tasks', [dupListOfTasks::class, 'newTasks'])
        ->name('branch-manager.tasks');
    });

    Route::prefix('office-chief')->group(function () {
        Route::get('/tasks', [dupListOfTasks::class, 'newTasks'])
        ->name('office-chief.tasks');
    });
});