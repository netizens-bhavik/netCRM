<?php

use Twilio\Rest\Client;

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
