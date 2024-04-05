<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout',[AuthController::class,'logout']);
    Route::post('user/profile',[UserController::class,'show']);

    Route::post('project/create',[ProjectController::class,'create']);
});
