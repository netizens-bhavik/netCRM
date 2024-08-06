<?php

namespace App\Services;
use App\Models\Notification;
use App\Models\Project;
use App\Models\ProjectHasComment;
use App\Models\ProjectHasMembers;
use App\Models\User;
use App\Traits\ApiResponses;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectHasCommentServices
{
    use ApiResponses;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index($projectId)
    {
        try {
            $comments = ProjectHasComment::with('user')->where('project_id', $projectId)->latest()->paginate(10);
            return response()->json(['status' => 'success', 'data' => $comments]);
        } catch (Exception $e) {
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }

    public function store($request, $projectId)
    {
        try {
            DB::beginTransaction();
            $authUser = Auth::user();
            $comment = ProjectHasComment::create([
                'user_id' => $authUser->id,
                'project_id' => $projectId,
                'comment' => $request->comment
            ]);
            $projectData = Project::find($projectId);
            $projectMembers = ProjectHasMembers::where('project_id', $projectId)
                        ->get()
                        ->pluck('user_id')
                        ->unique()
                        ->values()
                        ->toArray();
            $userData = User::with('token')->has('token')->whereIn('id',$projectMembers)->get()->toArray();
            if(!empty($userData))
            {
                foreach ($userData as $userDataResponse) {
                    Notification::create([
                        'title' => 'Project Comment',
                        'description' => $authUser->name.' has commented on the " '.$projectData->name.' "',
                        'user_id' => $userDataResponse['id'],
                        'refrence_id' => $projectId,
                        'type' => 'Project'
                    ]);
                    $device_token = $userDataResponse['token'];
                    foreach ($device_token as $value) {
                        if(!empty($value['device_token']))
                        {
                            send_firebase_notification($value['device_token'],'Comment' ,$authUser->name.' has commented on the " '.$projectData->name.' "');
                        }

                    }
                    
                }
            }
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $comment]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse([], $e->getMessage(), 500);
        }
    }

    public function edit($commentId)
    {
        try {
            $comment = ProjectHasComment::find($commentId);
            if ($comment) {
                return response()->json(['status' => 'success', 'data' => $comment]);
            } else {
                throw new Exception('Comment Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }

    public function update($request, $commentId)
    {
        try {
            $comment = ProjectHasComment::find($commentId);
            if ($comment) {
                $comment->update([
                    'comment' => $request->comment
                ]);
                return response()->json(['status' => 'success', 'data' => $comment]);
            } else {
                throw new Exception('Comment Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }

    public function destroy($commentId)
    {
        try {
            $comment = ProjectHasComment::find($commentId);
            if ($comment) {
                $comment->delete();
                return response()->json(['status' => 'success', 'message' => 'Comment Delete Successfully.']);
            } else {
                throw new Exception('Comment Not Found');
            }
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
