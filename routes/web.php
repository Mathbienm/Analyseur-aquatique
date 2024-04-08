<?php

use Illuminate\Support\Facades\Route;



Route::get('/', [\App\Http\Controllers\pageController::class, 'page']);

Route::get('/bassins/export', [\App\Http\Controllers\bassinController::class, 'export'])->name('bassins.export');

Route::get('/modifier/temperature', [\App\Http\Controllers\modifiertempController::class, 'modifier.temperature'])->name('modifier.temperature');

Route::get('/updateThreshold', [\App\Http\Controllers\bassinController::class, 'updateThreshold']);

Route::get('/updatePh', [\App\Http\Controllers\bassinController::class, 'updatePh']);

Route::get('/createTemperature', [\App\Http\Controllers\arduinoController::class, 'createTemperature']);

