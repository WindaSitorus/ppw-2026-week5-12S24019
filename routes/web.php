<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

Route::resource('buku', BukuController::class);