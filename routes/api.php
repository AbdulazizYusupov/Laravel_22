<?php

use App\Http\Controllers\Bot;
use App\Http\Controllers\BotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/set', [Bot::class, 'set'])->name('set');
Route::post('/get', [Bot::class, 'get'])->name('get');

Route::post('/bot', [BotController::class, 'bot']);
