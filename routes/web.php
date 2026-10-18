<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

// Rute standar untuk CRUD Buku
Route::resource('buku', BukuController::class);

// Rute baru untuk fitur Transaksi ACID
Route::post('/buku/{id}/pinjam', [BukuController::class, 'pinjam'])->name('buku.pinjam');
Route::post('/peminjaman/{id}/kembali', [BukuController::class, 'kembali'])->name('buku.kembali');