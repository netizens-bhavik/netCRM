<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Task;
use Exception;
use App\Traits\ApiResponses;
use App\Models\TaskHasComment;
use App\Models\User;
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
            $comments = TaskHasComment::with('user')->where('task_id', $taskId)->latest()->paginate(10);
            return response()->json(['status' => 'success', 'data' => $comments]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function store($request, $taskId)
    {
        try {
            TaskHasComment::create(['user_id' => Auth::id(), 'task_id' => $taskId, 'comment' => $request->comment]);
            $userIds = Task::with(['members.user:id', 'observers.user:id'])
            ->where('id', $taskId)
            ->get()
            ->flatMap(function ($task) {
                return array_merge(
                    $task->members->pluck('user_id')->toArray(),
                    $task->observers->pluck('observer_id')->toArray(),
                    [$task->assigned_to, $task->created_by]
                );
            })
            ->filter(function ($userId) {
                return $userId !== Auth::id();
            })
            ->unique()
            ->values()
            ->toArray();
            $userData = User::with('token')->has('token')->whereIn('id',$userIds)->get()->toArray();
            $taskData = Task::find($taskId);
            if(!empty($userData))
            {
                foreach ($userData as $userDataResponse) {
                    $device_token = $userDataResponse['token'];
                    foreach ($device_token as $value) {
                        if(!empty($value['device_token']))
                        {
                            if(!empty($value['user_id']))
                            {
                                Notification::create([
                                    'title' => 'Task Comment',
                                    'description' => $userDataResponse['name'].' has commented on the " '.$taskData->name.' "',
                                    'user_id' => $value['user_id'],
                                    'refrence_id' => $taskData->id,
                                    'type' => 'Task'
                                ]);
                            }
                            send_firebase_notification($value['device_token'],'Comment' ,$userDataResponse['name'].' has commented on the " '.$taskData->name.' "');
                        }

                    }
                    
                }
            }
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
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function update($request, $commentId)
    {
        try {
            $comment = TaskHasComment::find($commentId);
            if ($comment) {
                $comment->update(['comment' => $request->comment]);
                return response()->json(['status' => 'success', 'message' => 'Comment Update Successfully.']);
            } else {
                throw new Exception('Comment Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function destroy($commentId)
    {
        try {
            $comment = TaskHasComment::find($commentId);
            if ($comment) {
                $comment->delete();
                return response()->json(['status' => 'success', 'message' => 'Comment Delete Successfully.']);
            } else {
                throw new Exception('Comment Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function getTaskComment($taskId)
    {
        try {
            $comments = TaskHasComment::with('user')
                ->where('task_id', $taskId)
                ->orderBy('updated_at', 'asc') // Ordering by 'updated_at' in ascending order
                ->get();

            return response()->json(['status' => 'success', 'data' => $comments]);
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
