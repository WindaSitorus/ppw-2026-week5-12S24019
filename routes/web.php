<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

// Rute Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Perpustakaan (Dikunci, wajib login)
Route::middleware('auth')->group(function () {
    Route::resource('buku', BukuController::class);
    Route::post('/buku/{id}/pinjam', [BukuController::class, 'pinjam'])->name('buku.pinjam');
    Route::post('/peminjaman/{id}/kembali', [BukuController::class, 'kembali'])->name('buku.kembali');
});