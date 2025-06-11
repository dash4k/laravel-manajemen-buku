<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BukuController::class, 'index']);
Route::resource('buku', BukuController::class);