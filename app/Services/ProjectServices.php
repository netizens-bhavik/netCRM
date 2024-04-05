<?php

namespace App\Services;

use App\Models\Project;

class ProjectServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function createProject($request){
        try {
            $project = Project::create($request->all());
            return response()->json(['status' => 'success','message' => 'Project Create Successfully.']);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }
}
