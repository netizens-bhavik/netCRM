<?php

use App\Models\UserHasToken;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function send_whatsapp_notification()
{
    $sid = env('TWILIO_ACCOUNT_SID');
    $token  = env('TWILIO_AUTH_TOKEN');
    $twilio = new Client($sid, $token);

    $message = $twilio->messages
      ->create("whatsapp:+917046260656", // to
        array(
          "from" => "whatsapp:+14155238886",
          "body" => 'TESTING'
        )
      );
    return $message;
}

function send_firebase_notification($deviceToken,$title,$body)
{
    try {
        $json_credentials = file_get_contents(storage_path(env('FIREBASE_CREDENTIALS')));
        // // Log the contents
        // Log::info($json_credentials);

        Log::info("device token : $deviceToken");
        Log::info("title : $title");
        Log::info("body : $body");

        $firebase = (new Factory)->withServiceAccount($json_credentials);
        $messaging = $firebase->createMessaging();

        // Create a notification
        $notification = Notification::create($title, $body);
        UserHasToken::updateOrCreate(['device_token' => $deviceToken], ['is_sent'=>1]);
        // Create a CloudMessage
        $message = CloudMessage::withTarget('token', $deviceToken)
            ->withNotification($notification);
            $messaging->send($message);

        Log::info("message send to this device token => $deviceToken");
        Log::info("============================================");
    } catch (\Throwable $th) {
        Log::info($th);
    }


}
