<?php

use Illuminate\Support\Facades\Route;



Route::get('/', [\App\Http\Controllers\pageController::class, 'page']);

Route::get('/bassins/export', [\App\Http\Controllers\bassinController::class, 'export'])->name('bassins.export');

Route::get('/modifier/temperature', [\App\Http\Controllers\modifiertempController::class, 'modifier.temperature'])->name('modifier.temperature');

Route::get('/updateThreshold', [\App\Http\Controllers\bassinController::class, 'updateThreshold'])->name('updateThreshold');

Route::get('/updatePh', [\App\Http\Controllers\bassinController::class, 'updatePh']);

Route::get('/createTemperature', [\App\Http\Controllers\arduinoController::class, 'createTemperature']);

Route::get('/mesures/{bassin}', [\App\Http\Controllers\mesuresController::class, 'getMesures'])->name('getMesures');

Route::get('/export/{bassinId}', [\App\Http\Controllers\bassinController::class,'exportBassin'])->name('bassins.exportBassin');

Route::get('/afficher_moyenne_temperature', [\App\Http\Controllers\pageController::class, 'afficherMoyenneTemperature'])->name('afficher.moyenne.temperature');
