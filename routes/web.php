<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('client',[ClientController::class,'index'])->name('client.index')->middleware('auth.basic');
Route::get('client-list',[ClientController::class,'clientList']);
Route::get('create/client',[ClientController::class,'create'])->name('client.create');
Route::post('client/store',[ClientController::class,'StoreClientForm']);
Route::get('client/{clientId}/edit',[ClientController::class,'edit'])->name('client.edit');
Route::post('client/update/{clientId}', [ClientController::class, 'update']);
Route::get('delete/{clientId}/delete',[ClientController::class, 'destroy']);

Route::get('project',[ProjectController::class,'index'])->name('project.index');
Route::get('project-list',[ProjectController::class,'projectList']);
Route::get('project/create',[ProjectController::class,'create'])->name('project.create');
Route::post('project-store',[ProjectController::class,'store']);
Route::get('project/{projectId}/edit',[ProjectController::class,'edit'])->name('project.edit');
Route::get('project/{projectId}/delete', [ProjectController::class, 'destroy']);
Route::post('project/{ProjectId}/update',[ProjectController::class,'update']);

Route::get('task',[TaskController::class,'index'])->name('task.index');
Route::get('task-list',[TaskController::class,'taskList']);
Route::get('task/create',[TaskController::class,'create'])->name('task.create');
Route::post('task-store',[TaskController::class,'store']);

//get Dynamic City
Route::any('get-states/{countryId}',[HomeController::class,'getStates']);
Route::any('get-cities/{stateId}',[HomeController::class,'getCities']);


Route::get('migrate-command',function(){
    Artisan::call('migrate:fresh --seed');
    return ("migrated.");
});
Route::get('optimize-clear-command',function(){
    Artisan::call('optimize:clear');
    return ("optimize cleared.");
});
