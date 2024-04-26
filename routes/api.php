<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TaskHasMembersController;
use App\Http\Controllers\ProjectHasMembersController;
use Twilio\Rest\Client;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('user/edit/{userId}', [UserController::class, 'edit']);
    Route::post('user/{UserId}/update', [UserController::class, 'update']);
    Route::get('user/profile', [UserController::class, 'show']);
    Route::get('userList', [UserController::class, 'userList']);
    Route::delete('users/{userId}', [UserController::class, 'userDelete']);
    Route::get('allUsers',[UserController::class,'allUsers']);
    Route::get('findUser/{userId}',[UserController::class,'findUser']);
    Route::post('reset-password/{userId}',[UserController::class,'resetPassword']);
    Route::post('forgot-password',[UserController::class,'forgotPassword']);

    Route::get('all-client-list', [ClientController::class, 'allClientList']);
    Route::post('client/create', [ClientController::class, 'store']);
    Route::get('client/edit/{clientId}', [ClientController::class, 'edit']);
    Route::post('client/update/{clientId}', [ClientController::class, 'update']);
    Route::delete('client/{clientId}/delete', [ClientController::class, 'destroy']);
    Route::get('allClient',[ClientController::class,'allClients']);

    Route::get('all-project-list', [ProjectController::class, 'allProjectList']);
    Route::post('project/create', [ProjectController::class, 'store']);
    Route::get('project/edit/{projectId}', [ProjectController::class, 'edit']);
    Route::post('project/update/{projectId}', [ProjectController::class, 'update']);
    Route::delete('project/{projectId}/delete', [ProjectController::class, 'destroy']);
    Route::get('all-project',[ProjectController::class,'allProjects']);

    Route::get('all-task-list', [TaskController::class, 'allTaskList']);
    Route::post('task/create', [TaskController::class, 'store']);
    Route::get('task/edit/{taskId}', [TaskController::class, 'edit']);
    Route::post('task/update/{taskId}', [TaskController::class, 'update']);
    Route::delete('task/{taskId}/delete', [TaskController::class, 'destroy']);

    Route::get('get-task-status', [TaskController::class, 'getTaskStatus']);
    Route::get('get-all-priorities', [TaskController::class, 'getAllPriorities']);

    Route::get('my-project',[ProjectController::class,'myProject']);
    Route::get('my-task',[TaskController::class,'myTask']);

    //statastics
    Route::get('client-has-project/{clientId}',[ClientController::class,'clientHasProject']);
    Route::get('project-has-task/{projectId}',[ProjectController::class,'projectHasTask']);
    Route::get('client-has-project/{clientId}', [ClientController::class, 'clientHasProject']);
    Route::get('project-has-task/{projectId}', [ProjectController::class, 'projectHasTask']);

    Route::get('Project-has-members/{projectId}', [ProjectHasMembersController::class, 'ProjectMembers']);
    Route::get('task-has-members/{taskId}', [TaskHasMembersController::class, 'taskMembers']);

    // find task with project id and set parameter status and priority
    Route::get('project-find/{projectId}', [ProjectController::class, 'findProject']);
    // Route::get('my-project', [ProjectController::class, 'myProject']);

    Route::get('task-find/{taskId}',[TaskController::class,'findTask']);
    // Route::get('my-task', [TaskController::class,'myTask']);

    //statastics
    Route::get('statastics', [HomeController::class, 'statastics']);

    //Country
    Route::get('all-countries', [HomeController::class, 'allCountries']);
    Route::any('all-states/{countryId}', [HomeController::class, 'getStates']);
    Route::any('all-cities/{stateId}', [HomeController::class, 'getCities']);

    //task status change
    Route::get('task-status-change/{taskId}/{status}',[TaskController::class,'statusChange']);
    //all projectList of user with pagination
    Route::get('user-projects/{userId}',[ProjectController::class,'userProject']);
    Route::get('user-Tasks/{userId}',[TaskController::class,'userTask']);

    //Notifications
    Route::get('notification/{userId}',[NotificationController::class,'index']);
    // Route::post('create-notification',[NotificationController::class,'create']);
    Route::post('notification-mark-as-read',[NotificationController::class,'markAsRead']);

    //role
    Route::get('all-roles',[RoleController::class,'allRole']);
    Route::get('get-all-roles',[RoleController::class,'getAllRole']);
    Route::post('role-create',[RoleController::class,'create']);
    Route::get('role-edit/{roleId}',[RoleController::class,'edit']);
    Route::post('role-update/{roleId}',[RoleController::class,'update']);
    Route::delete('role-delete/{roleId}',[RoleController::class,'destroy']);

    //permission
    Route::get('all-permission',[PermissionController::class,'index']);

    //top performer
    Route::get('top-performers',[HomeController::class,'topPerformers']);
});
