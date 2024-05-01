<?php

namespace App\Http\Controllers;

use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;

class FirebaseNotificationController extends Controller
{
    function index()
    {
        try {
            $response = FirebaseNotificationService::index();
            return $response;
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function sendNotification()
    {
        try {
            FirebaseNotificationService::sendPushNotification(
                $deviceToken,
                'Notification Title',
                'Notification Body'
            );

            return response()->json(['message' => 'Notification sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
