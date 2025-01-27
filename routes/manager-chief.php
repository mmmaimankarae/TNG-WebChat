<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;

Route::middleware(['access.jwt', 'authorize.pages'])->group(function () {
    Route::prefix('branch-manager')->group(function () {
        Route::get('/new-tasks', function () {
            return view('listOfTasks');
        })->name('branch-manager.tasks');
    });

    Route::prefix('office-chief')->group(function () {
        Route::get('/new-tasks', function () {
            return view('listOfTasks');
        })->name('office-chief.tasks');
    });
});