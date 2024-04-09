<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectHasMembers;
use App\Models\Task;
use Exception;
use Illuminate\Support\Str;

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
            return response()->json(['status' => 'success', 'message' => 'Project Create Successfully.']);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function editProject($projectId)
    {
        try {
            $projectMembers = ProjectHasMembers::where('project_id', $projectId)->get('user_id');
            if (!empty($projectMembers)) {
                $member = [];
                foreach ($projectMembers as $key => $value) {
                    $member[] = $value->user_id;
                }
            } else {
                $member = null;
            }
            $project = Project::find($projectId);
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
            return response()->json(['status' => 'success', 'data' => $data]);
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
                return response()->json(['status' => 'success', 'message' => 'Project Update Successfully.']);
            } else {
                throw new Exception('Project Not Found');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function deleteProject($projectId)
    {
        try {
            $project = Project::find($projectId);
            if (!empty($project)) {
                $projectMembers = ProjectHasMembers::where('project_id', $projectId)->delete();
                $project->delete();
                return response()->json(['status' => 'success', 'message' => 'Project Deleted Successfully.']);
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
}
