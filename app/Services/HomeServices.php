<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Task;
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
            $tasks = Task::where('manage_by', $userid);
            $taskCount = $tasks->count();
            $memberOfTasks = TaskHasMembers::where('user_id', $userid)->get(['task_id']);
            $memberOfTaskCount = $memberOfTasks->count();
            $_Taskcounts = ($taskCount + $memberOfTaskCount);

            //project Count
            $projects = Project::where('manage_by', $userid);
            $projectCount = $projects->count();
            $memberOfProjects = ProjectHasMembers::where('user_id', $userid);
            $memberOfProjectCount = $memberOfProjects->count();
            $_projectcount = ($projectCount + $memberOfProjectCount);

            $res = ['status' => 'success', 'data' => ['tasks' => $_Taskcounts, 'projects' => $_projectcount]];
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
    public static function allRole()
    {
        try {
            $roles = Role::roles;
            $data = [];
            foreach ($roles  as $key => $role) {
                $data[] = [
                    'label' => $role,
                    'value' => $key,
                ];
            }
            // $data['roles'] = $data;
            return response()->json(['status' => 'success', 'data' => $data], 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
