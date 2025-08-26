<?php
use App\Http\Controllers\UploadController;

Route::post('/uploads/ck', [UploadController::class, 'ck'])
    ->name('uploads.ck.api');

