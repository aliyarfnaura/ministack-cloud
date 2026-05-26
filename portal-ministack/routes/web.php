<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// halaman utama ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// halaman yang hanya bisa diaksesjika user sudah Login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// memanggil semua rute otomatis dari file auth
require __DIR__.'/auth.php';