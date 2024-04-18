<?php

namespace App\Services;

use Exception;
use DataTables;
use App\Models\Role;
use App\Models\Task;
use App\Models\Project;
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
                $destinationPath = 'voiceMemo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                // $voice_memo = $destinationPath . '/' . $myimage;
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
                'voice_memo' => $myimage,
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
            if ($task) {
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
            } else {
                throw new Exception('Task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function updateTask($request, $taskId)
    {
        try {
            $task = Task::find($taskId);
            if (isset($request->voice_memo)) {
                $destinationPath = 'voiceMemo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                // $voice_memo = $destinationPath . '/' . $myimage;
            } else {
                $myimage = $task->voice_memo;
            }
            if (!empty($task)) {
                $task->update([
                    'name' => $request->name,
                    'project_id' => $request->project_id,
                    'start_date' => $request->start_date,
                    'due_date' => $request->due_date,
                    'description' => $request->description,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'voice_memo' => $myimage,
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
            } else {
                throw new Exception('Task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function getAllStatus()
    {
        try {
            $status = Task::status;
            return response()->json(['status' => 'success', 'data' => $status]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function getAllPriorities()
    {
        try {
            $priorities = Task::priority;
            return response()->json(['status' => 'success', 'data' => $priorities]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function myTask()
    {
        try {

            $user = Auth::user();
            $tasks = Task::with('members.user','project','manageBy')
            ->whereHas('members', function ($query) use ($user){
                $query->where('user_id',$user->id);
            })
            ->Orwhere('manage_by',$user->id)->get()->toArray();

            // $userid = Auth::id();
            // $_tasks = [];
            // $tasks = Task::where('manage_by', $userid)->get(['name', 'start_date', 'due_date', 'description', 'priority', 'status', 'voice_memo']);
            // foreach ($tasks as $key => $task) {
            //     $_tasks[] = [
            //         'name' => $task->name,
            //         'start_date' => $task->start_date,
            //         'due_date' => $task->due_date,
            //         'description' => $task->description,
            //         'priority' => $task->priority,
            //         'status' => $task->status,
            //         'voice_memo' => $task->voice_memo
            //     ];
            // }
            // $memberOfTasks = TaskHasMembers::where('user_id', $userid)->get(['task_id']);
            // foreach ($memberOfTasks as $key => $memberOfTask) {
            //     $t = Task::find($memberOfTask->task_id)->first(['name', 'start_date', 'due_date', 'description', 'priority', 'status', 'voice_memo']);
            //     $_tasks[] = [
            //         'name' => $t->name,
            //         'start_date' => $t->start_date,
            //         'due_date' => $t->due_date,
            //         'description' => $t->description,
            //         'priority' => $t->priority,
            //         'status' => $t->status,
            //         'voice_memo' => $t->voice_memo
            //     ];
            // }
            // return response()->json(['status' => 'success', 'data' => $_tasks]);
            // $user = Auth::user();
            // $_tasks = [];


            return response()->json(['status' => 'success', 'data' => $tasks]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allTaskList()
    {
        try {
            $tasks = Task::with('project', 'members.user', 'manageBy')->paginate(10);
            return response()->json(['status' => 'success', 'data' => $tasks], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function index()
    {
        try {
            return view('Task.index');
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function taskList()
    {
        try {
            $data = [];
            $i = 1;
            $tasks = Task::latest('created_at')->get(['id', 'name', 'start_date', 'due_date', 'description']);
            foreach ($tasks as $key => $task) {
                $data[] = [
                    '#' => $i++,
                    'name' => $task->name,
                    'startDate' => $task->start_date,
                    'deadLine' => $task->due_date,
                    'action' => 'action'
                    // 'action' => "<a href='" . url('project/' . $project->id . '/edit') . "' class='me-3'><i class='ti ti-edit'></i></a><a href='" . url('project/' . $project->id . '/delete') . "'><i class='ti ti-trash'></i></a>"
                ];
            }
            return Datatables::of($data)->rawColumns(['name', 'action'])->make(true);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function create()
    {
        try {

            // $projects = Project::
            return view('Task.create');
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function findTask($taskId)
    {
        try {
            $task = Task::with('project', 'members.user', 'manageBy')->find($taskId);
            return response()->json(['status' => 'success','data' => $task]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
