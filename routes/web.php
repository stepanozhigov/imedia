<?php

use App\Http\Controllers\CallController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CallController::class,'index'])->name('dashboard');

Route::get('/overloads', function () {
    return inertia('Overloads');
})->name('overloads');

Route::get('/maxloads', function () {
    return inertia('MaxLoads');
})->name('maxloads');

Route::get('cache/clear', [CallController::class,'cacheClear'])->name('cache.clear');
