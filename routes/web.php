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

Route::get('/search/global', [UserController::class, 'searchAll']);

Route::get('/user/search', [UserController::class, 'search']);
Route::get('/user/{name}', [UserController::class, 'show']);

Route::get('/team/teams', [TeamController::class, 'index']);
Route::get('/team/{teamName}/get', [TeamController::class, 'show']);
Route::get('/teams/search', [TeamController::class, 'search']);



Route::get('/files/{username}/{projectName}', [FileController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {

 
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/{name}/edit', [UserController::class, 'update']);
    Route::get('/user/{name}/invites', [UserController::class, 'invites']);
    Route::post('user/invite/{invite}/accept', [UserController::class, 'acceptInvite']);
    Route::post('user/invite/{invite}/reject', [UserController::class, 'rejectInvite']);


    Route::get('projects', [ProjectController::class, 'index']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::get('projects/{name}', [ProjectController::class, 'show']);
    Route::get('projects/{name}/tasks', [ProjectController::class, 'showTasks']);
    Route::put('projects/{name}', [ProjectController::class, 'update']);
    Route::delete('projects/{name}', [ProjectController::class, 'destroy']);
    Route::post('projects/search', [ProjectController::class, 'search']);

    Route::post('invitations/{invitation}/accept', [ProjectController::class, 'acceptInvitation']);
    Route::post('invitations/{invitation}/reject', [ProjectController::class, 'rejectInvitation']);



    Route::prefix('project')->group(function (){



        Route::get('/{projectName}/teams/invites', [ProjectController::class, 'showInvites']);
        Route::post('/{projectName}/teams/invite', [ProjectController::class, 'inviteTeam']);
        Route::delete('/{projectName}/teams/invite/{teamId}', [ProjectController::class, 'deleteInviteTeam']);
        Route::delete('/{project}/team/delete/{team}', [ProjectController::class, 'deleteTeam']);
        Route::delete('/{project}/team/delete/{team}/name', [ProjectController::class, 'deleteTeamOnName']);

        Route::post('/{projectId}/file/create', [FileController::class, 'store']);
        Route::post('/{projectId}/folder/create', [FileController::class, 'storeFolder']);
        Route::delete('/{projectId}/delete/{objectId}', [FileController::class, 'destroy']);
    
        Route::put('/{projectId}/object/update/{objectId}', [FileController::class, 'updateObject']);
        Route::put('/{projectId}/object/move/{objectId}', [FileController::class, 'moveObject']);
        Route::put('/{projectId}/file/update/{fileId}', [FileController::class, 'updateContent']);



        Route::put('/setting/{project}/name/change', [ProjectController::class, 'changeName']);
        Route::put('/setting/{project}/visibility/change', [ProjectController::class, 'changeVisibility']);
        Route::put('/setting/{project}/owner/change', [ProjectController::class, 'changeOwner']);
        Route::delete('/setting/{project}', [ProjectController::class, 'destroyRepository']);
    });

    
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
   Route::post('/team/{teamid}/invite/{userId}', [TeamController::class, 'sendInvite']);
   Route::delete('/team/{invite}/invite', [TeamController::class, 'deleteInviteUser']);
   Route::get('/team/{teamid}/invited', [TeamController::class, 'invitedPeople']);
    Route::post('/team/{teamid}/change', [TeamController::class, 'update']);
    Route::delete('/team/{teamid}/delete', [TeamController::class, 'destroy']);
});


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
