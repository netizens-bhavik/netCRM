<?php

namespace App\Services;

use App\Models\ProjectHasMembers;
use Exception;

class ProjectHasMembersServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function projectMember($projectId)
    {
        try {
            $members = ProjectHasMembers::where('project_id', $projectId)->get();
            if (!$members->isEmpty()) {
                $_members = [];
                $data = [];
                foreach ($members as $key => $member) {
                    $_members[] = $member->user_id;
                }
                $data['members'] = $_members;
                $response = ['status' => 'success', 'data' => $data];
                return response()->json($response, 200);
            } else {
                throw new Exception('This Project Has No Members');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
