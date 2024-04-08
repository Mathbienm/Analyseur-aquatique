<?php

use Illuminate\Support\Facades\Route;



Route::get('/', [\App\Http\Controllers\pageController::class, 'page']);

Route::get('/bassins/export', [\App\Http\Controllers\bassinController::class, 'export'])->name('bassins.export');

Route::get('/modifier/temperature', [\App\Http\Controllers\modifiertempController::class, 'modifier.temperature'])->name('modifier.temperature');

Route::post('/updateThreshold', [\App\Http\Controllers\bassinController::class, 'updateThreshold']);

Route::post('/updatePh', [\App\Http\Controllers\bassinController::class, 'updatePh']);

Route::post('/createTemperature', [\App\Http\Controllers\arduinoController::class, 'createTemperature']);

