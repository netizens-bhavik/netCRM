<?php

namespace App\Http\Controllers;

use App\Services\TaskHasMembersServices;
use Illuminate\Http\Request;

class TaskHasMembersController extends Controller
{
    function taskMembers($taskId){
        try {
            $response = TaskHasMembersServices::taskMember($taskId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
