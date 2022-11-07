<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;


Route::middleware(['checkDataFile'])->group(function() {

    Route::get('/', [CallController::class,'index'])->name('dashboard');
    Route::get('/overloads', [CallController::class,'overloadsIndex'])->name('overloads');
    Route::get('/maxloads', [CallController::class,'maxLoadsIndex'])->name('maxloads');

});
Route::get('/upload', [UploadController::class,'index'])->name('upload.get');
Route::post('/upload', [UploadController::class,'upload'])->name('upload.post');

Route::get('cache/clear', [CallController::class,'cacheClear'])->name('cache.clear');
