<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Models\TaskHasMembers;
use App\Models\ProjectHasMembers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function statastics()
    {
        try {
            $userid = Auth::id();
            // Task Count

            // $tasks = Task::where('manage_by', $userid);
            // $taskCount = $tasks->count();
            // $memberOfTasks = TaskHasMembers::where('user_id', $userid)->get(['task_id']);
            // $memberOfTaskCount = $memberOfTasks->count();
            // $_Taskcounts = ($taskCount + $memberOfTaskCount);

            //project Count
            // $projects = Project::where('manage_by', $userid);
            // $projectCount = $projects->count();
            // $memberOfProjects = ProjectHasMembers::where('user_id', $userid)->get(['project_id']);
            // $memberOfProjectCount = $memberOfProjects->count();
            // // dd($projectCount,$memberOfProjectCount);
            // $_projectcount = ($projectCount + $memberOfProjectCount);
            $taskCount = Task::count();
            $_projectcount = Project::count();
            $clientsCount = Client::count();
            $nonAdminUsers = User::with('roles')->withoutRole('super-admin')->count();
            $res = ['status' => 'success', 'data' => ['tasks' => $taskCount, 'projects' => $_projectcount,'client' => $clientsCount,'users' => $nonAdminUsers]];
            return response()->json($res);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function getAllCountries()
    {
        try {
            $countries = DB::table('countries')->where('name', 'India')->get(['name', 'id']);
            return response()->json(['status' => 'success', 'data' => $countries], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function getStates($countryId)
    {
        try {
            $states = DB::table('states')->where('country_id', $countryId)->get(['name', 'id']);
            return response()->json(['status' => 'success', 'data' => $states], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function getCities($stateId)
    {
        try {
            $cities = DB::table('cities')->where('state_id', $stateId)->get(['name', 'id']);
            return response()->json(['status' => 'success', 'data' => $cities], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }

    public static function topPerformers(){
        try {
            $topPerformers = User::withCount([
                'tasks as completed_count' => function($query) {
                    $query->where('status', 'Completed');
                },
                'tasks as pending_count' => function($query) {
                    $query->whereIn('status', ['Pending', 'Hold', 'In-progress']);
                },
                'tasks as overdue_count' => function($query) {
                    $query->whereNotNull('due_date')
                          ->where('status', 'Completed')
                          ->whereColumn('completed_date', '>', 'due_date');
                }
            ])
            ->whereHas('tasks', function($query) {
                $query->whereIn('status', ['Pending', 'Hold', 'In-progress', 'Completed']);
            })
            ->get();


            // $topPerformers = User::withCount(['tasks' => function ($query) {
            //     $query->where('status', 'Completed');
            //     $query->where('status','Pending');
            //     $query->where('user_id');
            // }])
            // ->having('tasks_count', '>', 0) // Only users with completed tasks
            // ->orderByDesc('tasks_count')
            // ->get();
            return response()->json(['top_performers' => $topPerformers]);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
