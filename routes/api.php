<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ServiceController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
Route::post('/newOrder', [OrderController::class, 'newOrder']);
Route::get('/getServices', [ServiceController::class, 'getList']);
