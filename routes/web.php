<?php

use App\Http\Controllers\TopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopController::class, 'index'])->name('top.index');
Route::post('/', [TopController::class, 'fetch'])->name('top.fetch');
