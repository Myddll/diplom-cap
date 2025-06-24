<?php

use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
Route::post('/newOrder', [OrderController::class, 'newOrder']);
