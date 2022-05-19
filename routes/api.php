<?php

use App\Http\Controllers\Api\ConverterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => '/v1'
], function() {
    Route::get('/help', [ConverterController::class, 'help']);
    Route::get('/convert', [ConverterController::class, 'convert']);
    Route::get('/currencies', [ConverterController::class, 'currencies']);
});
