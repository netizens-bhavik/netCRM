<?php

namespace App\Services;

use App\Traits\ApiResponses;
use App\Models\FirebaseNotification;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public static function storeToken($request)
    {
        try {
            $notification = FirebaseNotification::create(['device_token'=> $request->token,'user_id' => Auth::id()]);
if($notification){
    return response()->json(['status' => 'success', 'message' => 'Token Store Successfully.'], 200);
}
        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
    public static function sendPushNotification($deviceToken, $title, $body)
    {
        try {
            $messaging = app('firebase.messaging');

            $message = CloudMessage::new()
                ->withNotification(Notification::create($title, $body))
                ->withToken($deviceToken);

            $messaging->send($message);

        } catch (\Throwable $th) {
            return ApiResponses::errorResponse([], $th->getMessage(), 500);
        }
    }
}
