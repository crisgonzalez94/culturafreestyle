<?php

use App\Http\Controllers\Api\V1\EnvioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('envios', [EnvioController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('envios.store');
});
