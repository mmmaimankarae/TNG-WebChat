<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;

require __DIR__.'/sale-admin.php';
require __DIR__.'/internal-sale.php';
require __DIR__.'/manager-chief.php';

/* authentication เมื่อยังไม่ได้ signin */
Route::get('/', [dupFormAuthen::class, 'signin']);

Route::prefix('authen')->group(function () {
    Route::post('/', [authen::class, 'authValidate']);
    Route::post('/reset-forgot', [authen::class, 'forgotPopupValidate']);
});

Route::middleware(['access.jwt'])->group(function () {
    Route::prefix('authen')->group(function () {
        Route::get('/reset', [dupFormAuthen::class, 'reset']);
        Route::post('/reset-password', [authen::class, 'resetPopupValidate']);
    });
});

/* ออกจากระบบ */
Route::get('/signout', [authen::class, 'authenSignout']);