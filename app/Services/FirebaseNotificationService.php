<?php

namespace App\Services;

use App\Traits\ApiResponses;
use App\Models\UserHasToken;
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
        $user_id = Auth::id();
        $device_id = $request->device_id;
        $token = $request->token;

        $existingToken = UserHasToken::where('user_id', $user_id)
            ->where('device_id', $device_id)
            ->first();

        if ($existingToken) {
            // Update the existing token
            $existingToken->update(['device_token' => $token]);
            return response()->json(['status' => 'success', 'message' => 'Token updated successfully.'], 200);
        } else {
            // Create a new token record
            $notification = UserHasToken::create([
                'device_token' => $token,
                'device_id' => $device_id,
                'user_id' => $user_id
            ]);
            if ($notification) {
                return response()->json(['status' => 'success', 'message' => 'Token stored successfully.'], 200);
            }
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
