<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as VerifyCsrf;

Route::get('/', fn () => redirect()->route('notices.index'));

Route::resource('notices', NoticeController::class);
Route::post('/uploads/ck', [UploadController::class, 'ck'])
    ->name('uploads.ck')
    ->withoutMiddleware([VerifyCsrf::class]);
