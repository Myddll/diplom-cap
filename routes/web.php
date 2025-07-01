<?php

use App\Http\Controllers\Web\ExportController;
use App\Http\Controllers\Web\UserController;
use App\Http\Middleware\ExportMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index']);
Route::get('/export', [ExportController::class, 'export'])->middleware(ExportMiddleware::class);
