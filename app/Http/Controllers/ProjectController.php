<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Services\ProjectServices;

class ProjectController extends Controller
{
    public function create(ProjectCreateRequest $request)
    {
        try {
            $response = ProjectServices::createProject($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
