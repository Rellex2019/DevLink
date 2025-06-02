<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::get('/user/{name}', [UserController::class, 'show']);
Route::get('/team/teams', [TeamController::class, 'index']);
Route::get('/team/{teamName}/get', [TeamController::class, 'show']);
Route::get('/teams/search', [TeamController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {

 


    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/{name}/edit', [UserController::class, 'update']);




    Route::get('projects', [ProjectController::class, 'index']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects/{name}', [ProjectController::class, 'show']);
    Route::get('projects/{name}/tasks', [ProjectController::class, 'showTasks']);
    Route::put('projects/{name}', [ProjectController::class, 'update']);
    Route::delete('projects/{name}', [ProjectController::class, 'destroy']);
    Route::post('projects/search', [ProjectController::class, 'search']);

    
    Route::get('project/{projectName}/teams/invites', [ProjectController::class, 'showInvites']);
    Route::post('project/{projectName}/teams/invite', [ProjectController::class, 'inviteTeam']);
    Route::delete('project/{projectName}/teams/invite/{teamId}', [ProjectController::class, 'deleteInviteTeam']);
    Route::post('invitations/{invitation}/accept', [ProjectController::class, 'acceptInvitation']);
    Route::post('invitations/{invitation}/reject', [ProjectController::class, 'rejectInvitation']);

    Route::post('/project/{projectId}/file/create', [FileController::class, 'store']);
    Route::post('/project/{projectId}/folder/create', [FileController::class, 'storeFolder']);
    Route::delete('/project/{projectId}/delete/{objectId}', [FileController::class, 'destroy']);

    Route::put('/project/{projectId}/object/update/{objectId}', [FileController::class, 'updateObject']);
    Route::put('/project/{projectId}/object/move/{objectId}', [FileController::class, 'moveObject']);
    Route::put('/project/{projectId}/file/update/{fileId}', [FileController::class, 'updateContent']);

    Route::get('/files/{username}/{projectName}', [FileController::class, 'index']);
    //Task
    Route::prefix('projects/{projectName}/owner/{userName}/team/{teamName}')->group(function () {
        Route::get('/', [TaskController::class, 'show']);

        Route::prefix('statuses')->group(function () {
            Route::post('/', [TaskController::class, 'storeStatus']);
            Route::put('/{statusId}', [TaskController::class, 'updateStatus']);
            Route::delete('/{statusId}', [TaskController::class, 'destroyStatus']);
        });

        Route::prefix('tasks')->group(function () {
            Route::post('/', [TaskController::class, 'storeTask']);
            Route::put('/{taskId}', [TaskController::class, 'updateTask']);
            Route::delete('/{taskId}', [TaskController::class, 'destroyTask']);
        });
    });



   //Team
   Route::post('/team/create', [TeamController::class, 'store']);
   Route::get('/team/{teamid}/invite', [TeamController::class, 'invites']);
    Route::post('/team/{teamid}/change', [TeamController::class, 'update']);
    Route::delete('/team/{teamid}/delete', [TeamController::class, 'destroy']);
});


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
