<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskCommentRequest;
use App\Http\Requests\UpdateTaskCommentRequest;
use App\Services\TaskHasCommentServices;
use Illuminate\Http\Request;

class TaskHasCommentController extends Controller
{
    function index($taskId){
        try {
            $response = TaskHasCommentServices::index($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function store(CreateTaskCommentRequest $request,$taskId){
        try {
            $response = TaskHasCommentServices::store($request,$taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function edit($commentId){
        try {
            $response = TaskHasCommentServices::edit($commentId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function update(UpdateTaskCommentRequest $request,$commentId){
        try {
            $response = TaskHasCommentServices::update($request,$commentId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function destroy($commentId){
        try {
            $response = TaskHasCommentServices::destroy($commentId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function getTaskComment($taskId){
        try {
            $response = TaskHasCommentServices::getTaskComment($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
