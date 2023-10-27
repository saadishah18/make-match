<?php


namespace App\Notifications\Drivers;


use Twilio\Rest\Client;

class Twilio
{
    public function send($notifiable, $notification)
    {
        $client = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));
        $from_number = '';
        if($notifiable->country_code == '+1'){
            $from_number = config('services.twilio.us_number');
        }else{
            $from_number = config('services.twilio.number');
        }
        $client->messages->create($notifiable->country_code.''.$notifiable->phone, ['from' => $from_number, 'body' => $notification->message]);
    }
}
