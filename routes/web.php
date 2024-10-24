<?php

use App\Http\Controllers\TopController;
use App\Http\Controllers\YouTubeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopController::class, 'index'])->name('top.index');
Route::post('/', [TopController::class, 'fetch'])->name('top.fetch');

Route::get('/youtube', [YouTubeController::class, 'index'])->name('youtube.index');
Route::post('/youtube', [YouTubeController::class, 'fetch'])->name('youtube.fetch');
