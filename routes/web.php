<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DocumentController::class, 'index'])->name('dashboard');
    Route::post('/document/create', [DocumentController::class, 'store'])->name('document.store');

    // Real-time Editor & Fitur Kolaborasi
    Route::get('/document/{id}', [DocumentController::class, 'show'])->name('document.show');
    Route::post('/document/{id}/sync', [DocumentController::class, 'sync'])->name('document.sync');
    Route::post('/document/{id}/update', [DocumentController::class, 'update'])->name('document.update');
    Route::post('/document/{id}/cursor', [DocumentController::class, 'moveCursor']);
    Route::post('/revision/{id}/restore', [DocumentController::class, 'restore'])->name('revision.restore');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';