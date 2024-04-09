<?php

namespace App\Services;

use Exception;
use App\Models\TaskHasMembers;

class TaskHasMembersServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function taskMember($taskId)
    {
        try {
            $members = TaskHasMembers::where('task_id',$taskId)->get();
            if (!$members->isEmpty()) {
                $_members = [];
                foreach ($members as $key => $member) {
                    $_members[] = $member->user_id;
                }
                $data['members'] = $_members;
                $response = ['status' => 'success', 'data' => $data];
                return response()->json($response, 200);
            }else{
                throw new Exception('This Task Has No Members');
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
