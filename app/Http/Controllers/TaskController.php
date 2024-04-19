<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Services\TaskServices;

class TaskController extends Controller
{
    function store(TaskCreateRequest $request)
    {
        try {
            $response = TaskServices::createTask($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function edit($taskId)
    {
        try {
            $response = TaskServices::editTask($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function update(TaskUpdateRequest $request, $taskId)
    {
        try {
            $response = TaskServices::updateTask($request, $taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function destroy($taskId)
    {
        try {
            $response = TaskServices::deleteTask($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getTaskStatus()
    {
        try {
            $response = TaskServices::getAllStatus();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getAllPriorities()
    {
        try {
            $response = TaskServices::getAllPriorities();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function myTask()
    {
        try {
            $response = TaskServices::myTask();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function allTaskList()
    {
        try {
            $response = TaskServices::allTaskList();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function index()
    {
        try {
            $response = TaskServices::index();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function taskList()
    {
        try {
            $response = TaskServices::taskList();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function create(){
        try {
            $response = TaskServices::create();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function findTask($taskId){
        try {
            $response = TaskServices::findTask($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
