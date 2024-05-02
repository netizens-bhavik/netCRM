<?php

use Twilio\Rest\Client;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

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
    $messaging = app('firebase.messaging');

    $message = CloudMessage::new()
        ->withNotification(Notification::create($title, $body))
        ->withToken($deviceToken);

    $messaging->send($message);
}
