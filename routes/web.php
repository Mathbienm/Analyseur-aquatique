<?php

use Illuminate\Support\Facades\Route;



Route::get('/', [\App\Http\Controllers\pageController::class, 'page']);

Route::get('/bassins/export', [\App\Http\Controllers\bassinController::class, 'export'])->name('bassins.export');

Route::get('/modifier/temperature', [\App\Http\Controllers\modifiertempController::class, 'modifier.temperature'])->name('modifier.temperature');
