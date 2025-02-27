<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\controlAuthenticate as authen;
use App\Http\Controllers\controlDupView\dupFormAuthen;
use App\Http\Controllers\controlDupView\dupFormInvoice as invoice;
use App\Http\Controllers\controlDupView\dupInsertCSV as csv;
use App\Http\Controllers\controlGetInfo\msgInfo;
use App\Models\Tasks;
use App\Http\Controllers\sendMsg;
use App\Http\Controllers\controlStatus;

require __DIR__.'/sale-admin.php';
require __DIR__.'/internal-sale.php';
require __DIR__.'/manager-chief.php';
require __DIR__.'/it-support.php';

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
    /* แสดงรูปภาพ */
    Route::get('/preview/{messageId}', [msgInfo::class, 'previewImage'])->name('preview.image');
    Route::get('/download/{messageId}', [msgInfo::class, 'downloadImage'])->name('download.image');
    Route::post('/view', [msgInfo::class, 'viewImage'])->name('view.image');

    /* กรอกข้อมูลใบกำกับภาษี */
    Route::match(['get', 'post'], '/invoice', [invoice::class, 'default'])
        ->name('invoice');
});

/* กระบวนการส่ง Mag */
Route::get('/update-taskStatus', [Tasks::class, 'updateStatus']);
Route::post('/send-message', [sendMsg::class, 'sendMessage'])->name('send-message');
Route::post('/unsend', [sendMsg::class, 'unsendMsg'])->name('unsend');
  /* on status track */
Route::post('/quota', [controlStatus::class, 'quota'])->name('status.quota');

/* เพิ่มข้อมูล */
Route::post('/add-data', [csv::class, 'uploadCSV'])->name('add-data');
Route::post('/payment-desc', [csv::class, 'description'])
    ->name('sale-admin.payment-desc');

/* ออกจากระบบ */
Route::get('/signout', [authen::class, 'authenSignout']);
