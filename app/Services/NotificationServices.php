<?php

namespace App\Services;

use App\Models\Notification;
use Exception;

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
            $data = Notification::with('user')->where('user_id', $userId)->where('is_read', false)->latest()->get();
            $response = ['status' => 'success', 'data' => $data];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $res = ['status' => 'error', 'message' => $th->getMessage()];
            return response()->json($res);
        }
    }
    public static function markAsRead($request)
    {
        try {
            // $userId = $request->userId;
            $notificationIds = $request->notificationIds;
            if (!empty($notificationIds)) {
                foreach ($notificationIds as $key => $noti) {
                    Notification::find($noti)->update(['is_read' => true, 'read_at' => now()]);
                }
                return response()->json(['status' => 'success', 'message' => 'Notification updated Successfully.'], 200);
            } else {
                throw new Exception('Please Provide Notification IDs.');
            }
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
                        'user_id' => $user
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
