<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use Illuminate\Support\Str;
use App\Models\TaskHasMembers;
use Illuminate\Support\Facades\Auth;

class TaskServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function createTask($request)
    {
        try {
            if (isset($request->voice_memo)) {
                $destinationPath = 'voice_memo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                $voice_memo = $destinationPath . '/' . $myimage;
            } else {
                $voice_memo = '';
            }
            $task = Task::create([
                'name' => $request->name,
                'project_id' => $request->project_id,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
                'voice_memo' => $voice_memo,
                'manage_by' => Auth::id()
            ]);
            foreach ($request->task_members as $key => $value) {
                TaskHasMembers::create(['id' => Str::uuid(), 'task_id' => $task->id, 'user_id' => $value]);
            }
            return response()->json(['status' => 'success', 'message' => 'Task Create Successfully.']);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function editTask($taskId)
    {
        try {
            $task = Task::find($taskId);
            $taskMembers = TaskHasMembers::where('task_id', $taskId)->get('user_id');
            if (!empty($taskMembers)) {
                $member = [];
                foreach ($taskMembers as $key => $value) {
                    $member[] = $value->user_id;
                }
            } else {
                $member = null;
            }
            $data = [
                'name' => $task->name,
                'project_id' => $task->project_id,
                'start_date' => $task->start_date,
                'due_date' => $task->due_date,
                'description' => $task->description,
                'priority' =>  $task->priority,
                'status' => $task->status,
                'voice_memo' => url($task->voice_memo),
                'taskMembers' => $member,
            ];
            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function updateTask($request, $taskId)
    {
        try {
            if (isset($request->voice_memo)) {
                $destinationPath = 'voice_memo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                $voice_memo = $destinationPath . '/' . $myimage;
            } else {
                $voice_memo = '';
            }
            $task = Task::find($taskId);
            if (!empty($task)) {
                $task->update([
                    'name' => $request->name,
                    'project_id' => $request->project_id,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'description' => $request->description,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'voice_memo' => $voice_memo,
                    'manage_by' => Auth::id()
                ]);
                TaskHasMembers::where('task_id', $taskId)->delete();
                foreach ($request->task_members as $key => $value) {
                    TaskHasMembers::create(['id' => Str::uuid(), 'task_id' => $task->id, 'user_id' => $value]);
                }
                return response()->json(['status' => 'success', 'message' => 'Task Update Successfully.']);
            } else {
                throw new Exception('Task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteTask($taskId)
    {
        try {
            $task = Task::find($taskId);
            if (!empty($task)) {
                $projectMembers = TaskHasMembers::where('task_id', $taskId)->delete();
                $task->delete();
                return response()->json(['status' => 'success', 'message' => 'Task Deleted Successfully.']);
            }else{
                throw new Exception('Task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
