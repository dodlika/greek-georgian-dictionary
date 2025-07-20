<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WordController;

Route::get('/', [WordController::class, 'index'])->name('words.index');
Route::get('/words/create', [WordController::class, 'create'])->name('words.create');
Route::post('/words', [WordController::class, 'store'])->name('words.store');