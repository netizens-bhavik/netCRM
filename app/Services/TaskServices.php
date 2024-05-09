<?php

namespace App\Services;

use Exception;
use DataTables;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\TaskHasDocument;
use Illuminate\Support\Str;
use App\Models\TaskHasMembers;
use App\Models\TaskHasObservers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        // dd($request->all());

        try {
            if ($request->hasFile('voice_memo')) {
                $destinationPath = 'voiceMemo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                // $voice_memo = $destinationPath . '/' . $myimage;
            } else {
                $myimage = '';
            }
            $task = Task::create([
                'name' => $request->name,
                'project_id' => $request->has('project_id') ? $request->project_id : null,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
                'voice_memo' => $myimage,
                'created_by' => Auth::id(),
                'assigned_to'=>$request->assigned_to,
            ]);
            $documents = $request->file('document');
            if ($request->hasFile('document')) {
                $destinationPath = 'document';
                foreach ($documents as $doc) {
                    $originalFileName = $doc->getClientOriginalName();
                    $uniqueId = uniqid();$extension = $doc->getClientOriginalExtension();
                    $mydoc = time() . '_' . $uniqueId . '.' . $extension;
                    $doc->move(public_path($destinationPath), $mydoc);
                    TaskHasDocument::create(['document' => $mydoc,'task_id' => $task->id,'original_document_name'=>$originalFileName]);
                }
            } else {
                $mydoc = null;
            }
            $task_members = $request->task_members ?? [];
            $task_observers = $request->task_observers ?? [];
            if(!empty($task_members))
            {
                foreach ($task_members as $key => $value) {
                    TaskHasMembers::create(['id' => Str::uuid(), 'task_id' => $task->id, 'user_id' => $value]);
                }
            }
            if(!empty($task_observers))
            {
                foreach ($task_observers as $key => $value) {
                    TaskHasObservers::create(['task_id' => $task->id, 'observer_id' => $value]);
                }
            }
            $manageByArray = [$task->created_by];
            $userIds = array_merge($task_observers,$task_members, $manageByArray);
            $userIds = array_unique($userIds);
            $userData = User::with('token')->has('token')->whereIn('id',$userIds)->get()->toArray();
            // dump($userData);
            foreach ($userData as $userDataResponse) {
                $device_token = $userDataResponse['token'];
                foreach ($device_token as $value) {
                    if(!empty($value['device_token']))
                    {
                        send_firebase_notification($value['device_token'],'Your Task is Created',$request->name);
                    }
                }
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
                $taskObservers = TaskHasObservers::where('task_id',$taskId)->get('observer_id');
                $observers = [];
                if(!empty($taskObservers))
                {
                    foreach ($taskObservers as $key=>$value)
                    {
                        $observers [] = $value->observer_id;
                    }
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
                    'assigned_to' => $task->assigned_to,
                    'taskMembers' => $member,
                    'taskObservers' => $observers,
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

            $task = Task::findOrFail($taskId);

            $myimage = $task->voice_memo;

            if ($request->hasFile('voice_memo')) {
                $destinationPath = 'voiceMemo';
                $myimage = time() . '_' . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
            }

            $taskData = [
                'name' => $request->name,
                'project_id' => $request->project_id,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
                'voice_memo' => $myimage,
                'created_by' => Auth::id(),
                'assigned_to' => $request->assigned_to,
            ];

            DB::beginTransaction();

            $task->update($taskData);

            if ($request->hasFile('document')) {
                $destinationPath = 'document';
                foreach ($request->file('document') as $doc) {
                    $originalFileName = $doc->getClientOriginalName();
                    $uniqueId = uniqid();
                    $extension = $doc->getClientOriginalExtension();
                    $mydoc = time() . '_' . $uniqueId . '.' . $extension;
                    $doc->move(public_path($destinationPath), $mydoc);
                    TaskHasDocument::create([
                        'document' => $mydoc,
                        'task_id' => $task->id,
                        'original_document_name' => $originalFileName
                    ]);
                }
            }

            TaskHasMembers::where('task_id', $task->id)->delete();
            TaskHasObservers::where('task_id', $task->id)->delete();

            if (is_array($request->task_members)) {
                foreach ($request->task_members as $userId) {
                    TaskHasMembers::create(['task_id' => $task->id, 'user_id' => $userId]);
                }
            }
            if (is_array($request->task_observers)) {
                foreach ($request->task_observers as $observerId) {
                    TaskHasObservers::create(['task_id' => $task->id, 'observer_id' => $observerId]);
                }
            }
            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Task Update Successfully.']);

        } catch (\Throwable $th) {
            DB::rollback();
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
                $taskobservers = TaskHasObservers::where('task_id', $taskId)->delete();
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
            $tasks = Task::with('members.user','observers.user' ,'project', 'createdBy','assignedTo')
                ->whereHas('members', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->Orwhere('created_by', $user->id)->get()->toArray();

            return response()->json(['status' => 'success', 'data' => $tasks]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allTaskList($request)
    {
        try {
            if ($request->search && $request->sortBy && $request->order) {
                $tasks = Task::with('project', 'members.user', 'observers.user','createdBy','documents','assignedTo')->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)->paginate(10);
            } elseif ($request->search) {
                $tasks = Task::with('project', 'members.user', 'observers.user','createdBy','documents','assignedTo')->where('name', 'like', '%' . $request->search . '%')->paginate(10);
            } elseif ($request->sortBy && $request->order) {
                $tasks = Task::with('project', 'members.user', 'observers.user','createdBy','documents','assignedTo')->orderBy($request->sortBy, $request->order)->paginate(10);
            } else {
                $tasks = Task::latest()->with('project', 'members.user', 'observers.user','createdBy','documents','assignedTo')->paginate(10);
            }
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
            return DataTables::of($data)->rawColumns(['name', 'action'])->make(true);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function create()
    {
        try {

            $projects = Project::all(['id', 'name']);
            $users = User::withoutRole('super-admin')->get();
            $Status = Task::status;
            $priorities = Task::priority;
            return view('Task.create', compact('projects', 'users', 'Status', 'priorities'));
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function findTask($taskId)
    {
        try {
            $task = Task::with('project', 'members.user', 'observers.user','createdBy','documents','assignedTo')->find($taskId);
            if ($task) {
                return response()->json(['status' => 'success', 'data' => $task]);
            } else {
                throw new Exception('task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function statusChange($taskId, $Status)
    {
        try {
            $task = Task::find($taskId);
            if ($task) {
                $Statuses = Task::status;
                if (in_array($Status, $Statuses)) {
                    if($Status == 'Completed')
                    {
                        $task->update([
                            'completed_date'=> Carbon::now()->toDateString(),
                            'status' => $Status]);
                    }
                    else
                    {
                        $task->update(['status' => $Status]);
                    }
                    return response()->json(['status' => 'success', 'message' => 'Status Change Successfully.']);
                } else {
                    throw new Exception('Status Is In Correct.');
                }
            } else {
                throw new Exception('Task Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function userTask($request, $userId)
    {
        try {
            $user = User::find($userId);
            if ($user) {
                if ($request->search && $request->sortBy && $request->order) {
                    $tasks = Task::with('members.user','observers.user', 'project', 'createdBy','assignedTo')
                        ->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('created_by', $user->id)->paginate(10);
                } elseif ($request->search) {
                    $tasks = Task::with('members.user', 'observers.user','project', 'createdBy','assignedTo')
                        ->where('name', 'like', '%' . $request->search . '%')
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('created_by', $user->id)->paginate(10);
                } elseif ($request->sortBy && $request->order) {
                    $tasks = Task::with('members.user', 'observers.user','project', 'createdBy','assignedTo')->orderBy($request->sortBy, $request->order)
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('created_by', $user->id)->paginate(10);
                } else {
                    $tasks = Task::with('members.user','observers.user','project', 'createdBy','assignedTo')
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('created_by', $user->id)->paginate(10);
                }
            } else {
                throw new Exception('User Not Found.');
            }

            return response()->json(['status' => 'success', 'data' => $tasks]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteTaskDocument($documentId){
        try {
            $doc = TaskHasDocument::find($documentId);
            if ($doc) {
                $doc->delete();
                return response()->json(['status' => 'success', 'message' => 'Document Delete Successfully.']);
            }else{
                throw new Exception('Documnet Not Found.');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteVoiceMemo($taskId){
        try {
            $task = Task::find($taskId);
            if ($task) {
                $task->update(['voice_memo' => null]);
                return response()->json(['status' => 'success', 'message' => 'Voice Memo Remove Successfully.']);
        }else{
            throw new Exception('Task Not Found.');
        }
    } catch (\Throwable $th) {
        $res = ['status' => 'error', 'message' => $th->getMessage()];
        return response()->json($res);
    }
    }
}
