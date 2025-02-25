<?php

namespace App\Services;

use App\Models\Notification;
use Exception;
use DataTables;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Support\Str;
use App\Models\ProjectHasMembers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function createProject($request)
    {
        try {
            DB::beginTransaction();
            $project = Project::create([
                'client_id' => $request->has('client_id') ? $request->client_id : null,
                'manage_by' => $request->has('manage_by') ? $request->manage_by : null,
                'created_by' =>Auth::id(),
                'name' => $request->name,
                'start_date' => $request->has('start_date') ? $request->start_date : null,
                'deadline' => $request->has('deadline') ? $request->deadline : null,
                'summary' => $request->has('summary') ? $request->summary : null,
                'currency' => $request->has('currency') ? $request->currency : null,
            ]);
            $project_members = $request->project_members;
            foreach ($project_members as $key => $member) {
                ProjectHasMembers::create(['project_id' => $project->id, 'user_id' => $member]);
            }
            $userIds = array_unique($project_members);
            $userData = User::with('token')->has('token')->whereIn('id',$userIds)->get()->toArray();
            foreach ($userData as $userDataResponse) {
                Notification::create([
                    'title' => 'Project Created',
                    'description' => $project->name,
                    'user_id' => $userDataResponse['id'],
                    'refrence_id' => $project->id,
                    'type' => 'Project'
                ]);
                $device_token = $userDataResponse['token'];
                foreach ($device_token as $value) {
                    if(!empty($value['device_token']))
                    {
                        send_firebase_notification($value['device_token'],'Your Project is Created',$request->name);
                    }
                }
            }
            DB::commit();
            if ($request->expectsJson()) {
                //For API
                return response()->json(['status' => 'success', 'message' => 'Project Create Successfully.']);
            } else {
                //For WEB
                toastr()->success('Data has been saved successfully!');
                return redirect('project');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $res = ['status' => 'error', 'message' => $th->getMessage().$th->getLine()];
            return response()->json($res);
        }
    }
    public static function editProject($request, $projectId)
    {
        try {
            $project = Project::find($projectId);
            // dd($project);
            if ($project) {
                $projectMembers = ProjectHasMembers::where('project_id', $projectId)->get('user_id');
                if (!empty($projectMembers)) {
                    $member = [];
                    foreach ($projectMembers as $key => $value) {
                        $member[] = $value->user_id;
                    }
                } else {
                    $member = null;
                }

                $data = [
                    'client_id' => $project->client_id,
                    'manage_by' => $project->manage_by,
                    'created_by'=> $project->createdBy(),
                    'name' => $project->name,
                    'start_date' => $project->start_date,
                    'deadline' => $project->deadline,
                    'summary' => $project->summary,
                    'currency' => $project->currency,
                    'projectMembers' => $member
                ];
                // if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'data' => $data]);
                // } else {
                    // dd($data);
                //     $clients = Client::latest('created_at')->get(['id', 'name']);
                //     $users = User::withoutRole('super-admin')->latest('created_at')->get();
                //     return view('project.create', compact('clients', 'users', 'data'));
                // }
            } else {
                throw new Exception('Project Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function updateProject($request, $projectId)
    {
        try {
            $project = Project::find($projectId);
            if (!empty($project)) {
                $project->update([
                    'client_id' =>$request->has('client_id') ? $request->client_id : null,
                    'manage_by' => $request->has('manage_by') ? $request->manage_by : null,
                    'created_by'=> Auth::id(),
                    'name' => $request->name,
                    'start_date' => $request->has('start_date') ? $request->start_date : null,
                    'deadline' => $request->has('deadline') ? $request->deadline : null,
                    'summary' => $request->has('summary') ? $request->summary : null,
                    'currency' => $request->has('currency') ? $request->currency : null,
                ]);
                $projectMembers = ProjectHasMembers::where('project_id', $projectId)->delete();
                $project_members = $request->project_members;
                foreach ($project_members as $key => $member) {
                    ProjectHasMembers::create(['project_id' => $project->id, 'user_id' => $member]);
                }
                if ($request->expectsJson()) {
                    //For API
                    return response()->json(['status' => 'success', 'message' => 'Project Update Successfully.']);
                } else {
                    //For WEB
                    toastr()->success('Data has been Upadted successfully!');
                    return redirect('project');
                }
            } else {
                throw new Exception('Project Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteProject($request, $projectId)
    {
        // DB::beginTransaction();
        try {
            $project = Project::find($projectId);


            if (!empty($project)) {
                Notification::where('refrence_id',$projectId)
                ->where('type', 'project')
                ->delete();
                $projectMembers = Project::find($projectId)->delete();
                $project->delete();
                // DB::commit();
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'message' => 'Project Deleted Successfully.']);
                } else {
                    toastr()->success('Data has been Deleted successfully!');
                    return redirect('project');
                }
            } else {
                throw new Exception('Project Not Found');
            }
        } catch (\Throwable $th) {
            // DB::rollBack();
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function projectHasTasks($projectId)
    {
        try {
            $tasks = Task::where('project_id', $projectId)->get();
            if (!$tasks->isEmpty()) {
                $_tasks = [];
                foreach ($tasks as $key => $task) {
                    $_tasks[] = [
                        'name' => $task->name,
                        'start_date' => $task->start_date,
                        'due_date' => $task->due_date,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'status' => $task->status,
                        'voice_memo' => url($task->voice_memo),
                        'created_by' => $task->created_by
                    ];
                }
                $data['tasks'] = $_tasks;
                $response = ['status' => 'success', 'data' => $data];
                return response()->json($response, 200);
            } else {
                throw new Exception('No Task Of This Project.');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function projectFind($projectId, $request)
    {
        try {
            $project = Project::with('client', 'manageBy', 'createdBy','members.user', 'tasks.members.user')->find($projectId);
            if ($project) {
                $project->members->each(function ($member) {
                    $firstRole = $member->user->roles->first();
                    $roleName = $firstRole ? $firstRole->name : null;
                    // Assuming Role::roles is an array mapping role names to labels
                    $label = $roleName ? Role::roles[$roleName] ?? $roleName : null;

                    $member->user->roleName = ['value' => $roleName, 'label' => $label];
                    unset($member->user->roles);
                });
                $response = ['status' => 'success', 'data' => $project->toArray()];

                return response()->json($response, 200);
            } else {
                throw new Exception('Project Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function myProject()
    {
        try {
            $user = Auth::user();
            $projects = Project::with('members.user', 'client', 'manageBy','createdBy')
                ->whereHas('members', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->Orwhere('manage_by', $user->id)
                ->Orwhere('created_by', $user->id)
                ->get()->toArray();

            return response()->json(['status' => 'success', 'data' => $projects]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allProjectList($request)
    {
        try {
            $projectsQuery = Project::with('client', 'manageBy', 'createdBy', 'members.user');

            if ($request->search) {
                $projectsQuery->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->sortBy && $request->order) {
                $projectsQuery->orderBy($request->sortBy, $request->order);
            }

            if (!($request->search || ($request->sortBy && $request->order))) {
                $projectsQuery->has('members.user');
            }

            $projects = $projectsQuery->latest()->paginate(10);

            return response()->json(['status' => 'success', 'data' => $projects], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function index()
    {
        try {
            return view('project.index');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function projectList()
    {
        try {
            $data = [];
            $i = 1;
            $projects = Project::latest('created_at')->get(['id', 'name', 'start_date', 'deadline', 'summary']);
            foreach ($projects as $key => $project) {
                $data[] = [
                    '#' => $i++,
                    'name' => $project->name,
                    'startDate' => $project->start_date,
                    'deadLine' => $project->deadline,
                    'action' => "<a href='" . url('project/' . $project->id . '/edit') . "' class='me-3'><i class='ti ti-edit'></i></a><a href='" . url('project/' . $project->id . '/delete') . "'><i class='ti ti-trash'></i></a>"
                ];
            }
            return Datatables::of($data)->rawColumns(['name', 'action'])->make(true);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function create()
    {
        try {
            $clients = Client::latest('created_at')->get(['id', 'name']);
            $users = User::withoutRole('super-admin')->latest('created_at')->get();
            return view('project.create', compact('clients', 'users'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function allProjects($userId)
    {
        try {
            if ($userId == null) {
                $projects = Project::get();
            } else {
                $projects = Project::orWhereHas('members', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                    ->orWhere('manage_by', $userId)
                    ->orWhere('created_by', $userId)
                    ->get()
                    ->toArray();
            }
            return response()->json(['status' => 'success', 'data' => $projects], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function userProject($request, $userId)
    {
        try {
            $user = User::find($userId);
            $query = Project::with('members.user', 'client', 'manageBy','createdBy')
                ->where(function ($query) use ($user) {
                    $query->whereHas('members', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    })
                    ->orWhere('manage_by', $user->id)
                    ->orWhere('created_by', $user->id);
                });
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where('name', 'like', '%' . $searchTerm . '%');
            }

            if ($request->has('sortBy') && $request->has('order') && !empty($request->sortBy) && !empty($request->order)) {
                $sortBy = $request->sortBy;
                $order = $request->order;
                $query->orderBy($sortBy, $order);
            }

            $projects = $query->latest()->paginate(10);

            return response()->json(['status' => 'success', 'data' => $projects]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
