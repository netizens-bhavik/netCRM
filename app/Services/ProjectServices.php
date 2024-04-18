<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Support\Str;
use App\Models\ProjectHasMembers;
use DataTables;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

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
            $project = Project::create([
                'id' => Str::uuid(),
                'client_id' => $request->client_id,
                'manage_by' => $request->manage_by,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
                'summary' => $request->summary,
                'currency' => $request->currency,
            ]);
            $project_members = $request->project_members;
            foreach ($project_members as $key => $member) {
                ProjectHasMembers::create(['id' => Str::uuid(), 'project_id' => $project->id, 'user_id' => $member]);
            }
            if ($request->expectsJson()) {
                //For API
                return response()->json(['status' => 'success', 'message' => 'Project Create Successfully.']);
            } else {
                //For WEB
                toastr()->success('Data has been saved successfully!');
                return redirect('project');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function editProject($request, $projectId)
    {
        try {
            $project = Project::find($projectId);
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
                    'name' => $project->name,
                    'start_date' => $project->start_date,
                    'deadline' => $project->deadline,
                    'summary' => $project->summary,
                    'currency' => $project->currency,
                    'projectMembers' => $member
                ];
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'success', 'data' => $data]);
                } else {
                    $clients = Client::latest('created_at')->get(['id', 'name']);
                    $users = User::withoutRole('admin')->latest('created_at')->get();
                    return view('project.create', compact('clients', 'users', 'data'));
                }
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
                    'client_id' => $request->client_id,
                    'manage_by' => $request->manage_by,
                    'name' => $request->name,
                    'start_date' => $request->start_date,
                    'deadline' => $request->deadline,
                    'summary' => $request->summary,
                    'currency' => $request->currency,
                ]);
                $projectMembers = ProjectHasMembers::where('project_id', $projectId)->delete();
                $project_members = $request->project_members;
                foreach ($project_members as $key => $member) {
                    ProjectHasMembers::create(['id' => Str::uuid(), 'project_id' => $project->id, 'user_id' => $member]);
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
        try {
            $project = Project::find($projectId);
            if (!empty($project)) {
                $projectMembers = ProjectHasMembers::where('project_id', $projectId)->delete();
                $project->delete();
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
                        'manage_by' => $task->manage_by
                    ];
                }
                $data['tasks'] = $_tasks;
                $response = ['status' => 'success', 'data' => $data];
                return response()->json($response, 200);
            } else {
                throw new Exception('No Task Of THis Project.');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function projectFind($projectId, $request)
    {
        try {
            // dd($projectId, 'status', $request->status, 'priority', $request->priority);
            $project = Project::find($projectId);
            if ($project) {
                $data = [];
                $_project = [
                    'client_id' => $project->client_id,
                    'manage_by' => $project->manage_by,
                    'name' => $project->name,
                    'start_date' => $project->start_date,
                    'deadline' => $project->deadline,
                    'summary' => $project->summary,
                    'currency' => $project->currency
                ];
                $data['project'] = $_project;
                if ($request->status && $request->priority) {
                    $tasks = Task::where('project_id', $projectId)->where('status', $request->status)->where('priority', $request->priority)->get();
                } elseif ($request->status) {
                    $tasks = Task::where('project_id', $projectId)->where('status', $request->status)->get();
                } elseif ($request->priority) {
                    $tasks = Task::where('project_id', $projectId)->where('priority', $request->priority)->get();
                } else {
                    $tasks = Task::where('project_id', $projectId)->get();
                }
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
                            'manage_by' => $task->manage_by
                        ];
                    }
                    $data['tasks'] = $_tasks;
                }
                $response = ['status' => 'success', 'data' => $data];
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
            $projects = Project::with('members')
            ->whereHas('members', function ($query) use ($user){
                $query->where('user_id',$user->id);
            })
            ->Orwhere('manage_by',$user->id)->get()->toArray();
            // $data = [];
            // $_projects = [];
            // foreach ($projects as $key => $project) {

            //     $_projects[] = [
            //         'name' => $project->name,
            //         'start_date' => $project->start_date,
            //         'deadline' => $project->deadline,
            //         'summary' => $project->summary,
            //         'currency' => $project->currency
            //     ];
            // }
            // $memberOfProjects = ProjectHasMembers::where('user_id', $userid)->get(['project_id']);
            // foreach ($memberOfProjects as $key => $memberOfProject) {
            //     $p = Project::find($memberOfProject->project_id)->first(['name', 'start_date', 'deadline', 'summary', 'currency']);
            //     $_projects[] = [
            //         'name' => $p->name,
            //         'start_date' => $p->start_date,
            //         'deadline' => $p->deadline,
            //         'summary' => $p->summary,
            //         'currency' => $p->currency
            //     ];
            // }

            // // Retrieve projects where the user is the manager
            // $managerProjects = $user->projects()->whereNotNull('manage_by')->get();

            // // Retrieve projects where the user is a member
            // $memberProjects = $user->projects()->whereNull('manage_by')->get();

            // // Merge manager and member projects
            // $allProjects = $managerProjects->merge($memberProjects);
            // return($allProjects);

            return response()->json(['status' => 'success', 'data' => $projects]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function allProjectList()
    {
        try {
            $projects = Project::with('client', 'manageBy', 'members.user')->paginate(10);
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
            $users = User::withoutRole('admin')->latest('created_at')->get();
            return view('project.create', compact('clients', 'users'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public static function allProjects()
    {
        try {
            $projects = Project::all();
            return response()->json(['status' => 'success', 'data' => $projects], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
