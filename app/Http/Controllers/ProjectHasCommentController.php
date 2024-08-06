<?php

namespace App\Http\Controllers;

use App\Models\ProjectHasComment;
use App\Services\ProjectHasCommentServices;
use Exception;
use Illuminate\Http\Request;

class ProjectHasCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $projectHasCommentServices;
    public function __construct(ProjectHasCommentServices $projectHasCommentServices)
    {
        $this->projectHasCommentServices = $projectHasCommentServices;
    }
    public function index($projectId)
    {
        try {
            $response = $this->projectHasCommentServices->index($projectId);
            return $response;
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $projectId)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $projectId)
    {
        try {
            $response = $this->projectHasCommentServices->store($request, $projectId);
            return $response;
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectHasComment $projectHasComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectHasComment $projectHasComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectHasComment $projectHasComment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectHasComment $projectHasComment)
    {
        //
    }
}
