<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiayaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('biayas', BiayaController::class);

Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
