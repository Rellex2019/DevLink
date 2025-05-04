<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);





Route::get('projects', [ProjectController::class, 'index']);
Route::post('projects', [ProjectController::class, 'store']); 
Route::get('projects/{name}', [ProjectController::class, 'show']);
Route::put('projects/{name}', [ProjectController::class, 'update']);
Route::delete('projects/{name}', [ProjectController::class, 'destroy']);
Route::post('projects/search', [ProjectController::class, 'search']);


Route::post('/projects/{project}/files', [FileController::class, 'store']);
Route::put('/files/{file}/content', [FileController::class, 'updateContent']);


Route::get('/files/{username}/{projectName}', [FileController::class, 'index']);

Route::get('/{any}', function () {
    return view('app'); 
})->where('any', '.*');