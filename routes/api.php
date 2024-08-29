<?php

use App\Http\Controllers\ProjectHasCommentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FirebaseNotificationController;
use App\Http\Controllers\MemoController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TaskHasMembersController;
use App\Http\Controllers\ProjectHasMembersController;
use App\Http\Controllers\TaskHasCommentController;
use App\Models\TaskHasComment;
use App\Models\User;
use Carbon\Carbon;
use Twilio\Rest\Client;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::post('forgot-password',[UserController::class,'forgotPassword']);
Route::middleware('auth:sanctum')->group(function () {

    //users routes
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('user/edit/{userId}', [UserController::class, 'edit']);
    Route::post('user/{UserId}/update', [UserController::class, 'update']);
    Route::get('user/profile', [UserController::class, 'show']);
    Route::get('userList', [UserController::class, 'userList']);
    Route::delete('users/{userId}', [UserController::class, 'userDelete']);
    Route::get('allUsers',[UserController::class,'allUsers']);
    Route::get('findUser/{userId}',[UserController::class,'findUser']);
    Route::post('reset-password/{userId}',[UserController::class,'resetPassword']);

    //client routes
    Route::get('all-client-list', [ClientController::class, 'allClientList']);
    Route::post('client/create', [ClientController::class, 'store']);
    Route::get('client/edit/{clientId}', [ClientController::class, 'edit']);
    Route::post('client/update/{clientId}', [ClientController::class, 'update']);
    Route::delete('client/{clientId}/delete', [ClientController::class, 'destroy']);
    Route::get('allClient',[ClientController::class,'allClients']);

    //project routes
    Route::get('all-project-list', [ProjectController::class, 'allProjectList']);
    Route::post('project/create', [ProjectController::class, 'store']);
    Route::get('project/edit/{projectId}', [ProjectController::class, 'edit']);
    Route::post('project/update/{projectId}', [ProjectController::class, 'update']);
    Route::delete('project/{projectId}/delete', [ProjectController::class, 'destroy']);
    Route::get('all-project/{userId?}',[ProjectController::class,'allProjects']);

    //Project Comments Routes
    Route::get('project-comment/{projectId}',[ProjectHasCommentController::class,'index']);
    Route::post('project-comment/{projectId}',[ProjectHasCommentController::class,'store']);
    Route::get('project-comment/{commentId}/edit',[ProjectHasCommentController::class,'edit']);
    Route::post('project-comment/{commentId}/update',[ProjectHasCommentController::class,'update']);
    Route::delete('project-comment/{commentId}/delete',[ProjectHasCommentController::class,'destroy']);
    //without Pagination
    Route::get('get-project-comment/{projectId}',[ProjectHasCommentController::class,'getProjectComment']);
    //task routes
    Route::get('all-task-list', [TaskController::class, 'allTaskList']);
    Route::post('task/create', [TaskController::class, 'store']);
    Route::get('task/edit/{taskId}', [TaskController::class, 'edit']);
    Route::post('task/update/{taskId}', [TaskController::class, 'update']);
    Route::delete('task/{taskId}/delete', [TaskController::class, 'destroy']);
    //delete task Document
    Route::delete('delete-task-document/{documentId}',[TaskController::class,'deleteTaskDocument']);
    //delete voice Memo
    Route::delete('delete-voice-memo/{taskId}',[TaskController::class,'deleteVoiceMemo']);

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

    //Memo
    Route::resource('memo', MemoController::class)->except(['show','update']);
    Route::post('memo/{memoId}',[MemoController::class,'update']);

    Route::get('task-comment/{taskId}',[TaskHasCommentController::class,'index']);
    Route::post('task-comment/{taskId}',[TaskHasCommentController::class,'store']);
    Route::get('task-comment/{commentId}/edit',[TaskHasCommentController::class,'edit']);
    Route::post('task-comment/{commentId}/update',[TaskHasCommentController::class,'update']);
    Route::delete('task-comment/{commentId}',[TaskHasCommentController::class,'destroy']);
    //without Pagination
    Route::get('get-task-comment/{taskId}',[TaskHasCommentController::class,'getTaskComment']);

    //Pushnotification
    Route::post('store-device-token',[FirebaseNotificationController::class,'storeToken']);
    Route::get('pushNotification',[FirebaseNotificationController::class,'sendNotification']);

    Route::get('check-whatsapp',function(){
        $sid = env('TWILIO_ACCOUNT_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
          ->create("whatsapp:+917046260656", // to
            array(
              "from" => "whatsapp:+14155238886",
              "body" => 'TESTING messsage'
            )
          );
        return $message;
    });

});
