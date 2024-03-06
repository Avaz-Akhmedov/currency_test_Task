<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::get('/currencies/save', [CurrencyController::class, 'save'])->name('currencies.save');
Route::get('/currencies/json', [CurrencyController::class, 'currencies']);