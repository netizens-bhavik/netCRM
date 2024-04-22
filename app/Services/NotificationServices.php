<?php

namespace App\Services;

use App\Models\Notification;


class NotificationServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function index($userId)
    {
        try {
            $data = Notification::where('user_id',$userId)->where('is_read',false)->get();
            $response = ['status' => 'success', 'data' => $data];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function create($request)
    {
        try {
            $users = $request->user_id;
            if ($users) {
                foreach ($users as $key => $user) {
                    $notification = Notification::create([
                        'title' => $request->title,
                        'description' => $request->description,
                        'user_id' =>$user
                    ]);
                }
                $response = ['status' => 'success', 'message' => 'Notification Create Successfully.'];
            return response()->json($response, 200);
            }
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
}
