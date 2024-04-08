<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Services\ProjectServices;

class ProjectController extends Controller
{
    public function store(ProjectCreateRequest $request)
    {
        try {
            $response = ProjectServices::createProject($request);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    public function edit($projectId){
        try {
            $response = ProjectServices::editProject($projectId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    public function update(ProjectUpdateRequest $request,$projectId){
        try {
            $response = ProjectServices::updateProject($request,$projectId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    public function destroy($projectId){
        try {
            $response = ProjectServices::deleteProject($projectId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
