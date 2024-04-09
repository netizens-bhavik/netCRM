<?php

namespace App\Http\Controllers;

use App\Services\ProjectHasMembersServices;
use Illuminate\Http\Request;

class ProjectHasMembersController extends Controller
{
    function ProjectMembers($projectId){
        try {
            $response = ProjectHasMembersServices::projectMember($projectId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
