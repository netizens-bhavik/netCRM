<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectHasMembersController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskHasMembersController;
use App\Http\Controllers\UserController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout',[AuthController::class,'logout']);
    Route::post('user/profile',[UserController::class,'show']);

    Route::post('client/create',[ClientController::class,'store']);
    Route::get('client/edit/{clientId}',[ClientController::class,'edit']);
    Route::patch('client/update/{clientId}',[ClientController::class,'update']);
    Route::delete('client/{clientId}/delete',[ClientController::class,'destroy']);

    Route::post('project/create',[ProjectController::class,'store']);
    Route::get('project/edit/{projectId}',[ProjectController::class,'edit']);
    Route::patch('project/update/{projectId}',[ProjectController::class,'update']);
    Route::delete('project/{projectId}/delete',[ProjectController::class,'destroy']);

    Route::post('task/create',[TaskController::class,'store']);
    Route::get('task/edit/{taskId}',[TaskController::class,'edit']);
    Route::patch('task/update/{taskId}',[TaskController::class,'update']);
    Route::delete('task/{taskId}/delete',[TaskController::class,'destroy']);

    Route::get('get-task-status',[TaskController::class,'getTaskStatus']);
    Route::get('get-all-priorities',[TaskController::class,'getAllPriorities']);

    Route::get('client-has-project/{clientId}',[ClientController::class,'clientHasProject']);
    Route::get('project-has-task/{projectId}',[ProjectController::class,'projectHasTask']);

    Route::get('Project-has-members/{projectId}',[ProjectHasMembersController::class,'ProjectMembers']);
    Route::get('task-has-members/{taskId}',[TaskHasMembersController::class,'taskMembers']);

    // find task with project id and set parameter status and priority
    Route::get('project-find/{projectId}',[ProjectController::class,'findProject']);
});
