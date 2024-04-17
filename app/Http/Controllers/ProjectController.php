<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProjectServices;
use App\Http\Requests\FindProjectRequest;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;


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
    public function edit(Request $request,$projectId){
        try {
            $response = ProjectServices::editProject($request,$projectId);
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
    public function destroy(Request $request,$projectId){
        try {
            $response = ProjectServices::deleteProject($request,$projectId);
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function myProject(){
        try {
            $response = ProjectServices::myProject();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function allProjectList(){
        try {
            $response = ProjectServices::allProjectList();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function index(){
        try {
            $response = ProjectServices::index();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }

    function projectList(){
        try {
            $response = ProjectServices::projectList();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function create(){
        try {
            $response = ProjectServices::create();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
    function allProjects(){
        try {
            $response = ProjectServices::allProjects();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'error' => $th->getMessage()]);
        }
    }
}
