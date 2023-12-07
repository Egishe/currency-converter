<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tech/health', [\App\Http\Controllers\TechController::class, 'health']);
Route::group(['middleware' => [\App\Http\Middleware\VerifyApiAuthToken::class]], static function () {
    Route::get('/tech/guarded-health', [\App\Http\Controllers\TechController::class, 'guardedHealth']);
    Route::get('/currency/rates', [\App\Http\Controllers\CurrencyController::class, 'rates']);
    Route::post('/currency/convert', [\App\Http\Controllers\CurrencyController::class, 'convert']);
});
