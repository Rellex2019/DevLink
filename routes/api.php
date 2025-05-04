<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');







Route::middleware('auth:sanctum')->group(function () {


    Route::post('/logout', [AuthController::class, 'logout']);







});
