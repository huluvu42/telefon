<?php

use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\MobileListController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts', function () {
    return view('contacts.index');
})->name('contacts.index');

Route::prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/', [MobileListController::class, 'index'])->name('index');
    Route::get('/{sheetName}', [MobileListController::class, 'show'])->name('show');
    Route::get('/{sheetName}/pdf', [MobileListController::class, 'printPdf'])->name('pdf');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('contacts', ContactController::class);
    Route::resource('uploads', UploadController::class)->only(['index', 'create', 'store']);
});

require __DIR__.'/auth.php';
