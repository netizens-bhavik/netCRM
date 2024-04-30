<?php

namespace App\Services;

use Exception;
use App\Traits\ApiResponses;
use App\Models\TaskHasComment;
use Illuminate\Support\Facades\Auth;

class TaskHasCommentServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function index($taskId)
    {
        try {
            $comments = TaskHasComment::with('user')->where('task_id',$taskId)->latest()->paginate(10);
            return response()->json(['status' => 'success', 'data' => $comments]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function store($request, $taskId)
    {
        try {
            TaskHasComment::create(['user_id' => Auth::id(), 'task_id' => $taskId, 'comment' => $request->comment]);
            return response()->json(['status' => 'success', 'message' => 'Comment Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function edit($commentId)
    {
        try {
            $comment = TaskHasComment::find($commentId);
            if ($comment) {
                return response()->json(['status' => 'success', 'data' => $comment]);
            } else {
                throw new Exception('Comment Not Found');
            }
            return response()->json(['status' => 'success', 'message' => 'Comment Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function update($request,$commentId)
    {
        try {
            $comment = TaskHasComment::find($commentId);
            if ($comment) {
                $comment->update(['comment' => $request->comment]);
                return response()->json(['status' => 'success', 'message' => 'Comment Update Successfully.']);
            } else {
                throw new Exception('Comment Not Found');
            }
            return response()->json(['status' => 'success', 'message' => 'Comment Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function destroy($commentId){
        try {
            $comment = TaskHasComment::find($commentId);
            if ($comment) {
                $comment->delete();
                return response()->json(['status' => 'success', 'message' => 'Comment Delete Successfully.']);
            } else {
                throw new Exception('Comment Not Found');
            }
            return response()->json(['status' => 'success', 'message' => 'Comment Create Successfully.']);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function getTaskComment($taskId){
        try {
            $comments = TaskHasComment::with('user')->where('task_id',$taskId)->latest()->get();
            return response()->json(['status' => 'success', 'data' => $comments]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
