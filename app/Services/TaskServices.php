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
                'project_id' => $request->project_id,
                'start_date' => $request->start_date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'priority' => $request->priority,
                'status' => $request->status,
                'voice_memo' => $myimage,
                'manage_by' => Auth::id(),
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
            if ($request->hasFile('voice_memo')) {
                $destinationPath = 'voiceMemo';
                $myimage = time() . $request->voice_memo->getClientOriginalName();
                $request->voice_memo->move(public_path($destinationPath), $myimage);
                // $voice_memo = $destinationPath . '/' . $myimage;
            } else {
                $myimage = $task->voice_memo;
            }
            // if ($request->hasFile('document')) {
            //     if ($task->document && file_exists(public_path('document/' . $task->document))) {
            //         unlink(public_path('document/' . $task->document));
            //     }
            //     $destinationPath = 'document';
            //     $mydoc = time() . $request->document->getClientOriginalName();
            //     $request->document->move(public_path($destinationPath), $mydoc);
            //     // $voice_memo = $destinationPath . '/' . $myimage;
            // } else {
            //     $mydoc = $task->document;
            // }
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
                    'manage_by' => Auth::id(),
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
                }
                $allTaskMembers = TaskHasMembers::where('task_id', $task->id)->get();
                foreach ($allTaskMembers as $member) {
                    $member->delete();
                }

                // $task->members()->delete();
                foreach ($request->task_members as $key => $value) {
                    $arr = [
                        'task_id' => $task->id,
                        'user_id' => $value
                    ];
                    TaskHasMembers::updateOrCreate(['user_id' => $value], $arr);
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
            $tasks = Task::with('members.user', 'project', 'manageBy')
                ->whereHas('members', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->Orwhere('manage_by', $user->id)->get()->toArray();

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
                $tasks = Task::with('project', 'members.user', 'manageBy','documents')->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)->paginate(10);
            } elseif ($request->search) {
                $tasks = Task::with('project', 'members.user', 'manageBy','documents')->where('name', 'like', '%' . $request->search . '%')->paginate(10);
            } elseif ($request->sortBy && $request->order) {
                $tasks = Task::with('project', 'members.user', 'manageBy','documents')->orderBy($request->sortBy, $request->order)->paginate(10);
            } else {
                $tasks = Task::latest()->with('project', 'members.user', 'manageBy','documents')->paginate(10);
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
            return Datatables::of($data)->rawColumns(['name', 'action'])->make(true);
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
            $task = Task::with('project', 'members.user', 'manageBy','documents')->find($taskId);
            if ($task) {
                $task->members->each(function ($member) {
                    $firstRole = $member->user->roles->first();
                    $roleName = $firstRole ? $firstRole->name : null;
                    $label = $roleName ? Role::roles[$roleName] ?? $roleName : null;

                    $member->user->roleName = ['value' => $roleName, 'label' => $label];
                    unset($member->user->roles);
                });
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
                    $task->update(['status' => $Status]);
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
                    $tasks = Task::with('members.user', 'project', 'manageBy')
                        ->where('name', 'like', '%' . $request->search . '%')->orderBy($request->sortBy, $request->order)
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('manage_by', $user->id)->paginate(10);
                } elseif ($request->search) {
                    $tasks = Task::with('members.user', 'project', 'manageBy')
                        ->where('name', 'like', '%' . $request->search . '%')
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('manage_by', $user->id)->paginate(10);
                } elseif ($request->sortBy && $request->order) {
                    $tasks = Task::with('members.user', 'project', 'manageBy')->orderBy($request->sortBy, $request->order)
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('manage_by', $user->id)->paginate(10);
                } else {
                    $tasks = Task::with('members.user', 'project', 'manageBy')
                        ->whereHas('members', function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->Orwhere('manage_by', $user->id)->paginate(10);
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
}
