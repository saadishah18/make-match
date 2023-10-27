<?php
namespace App\Traits;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Jwt\JWT;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomV2Trait
{
    protected $client;
    public $access_token;
    public $headers;

    public function __construct()
    {
        $this->client = new Http();
        $this->access_token = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->access_token,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }
    public function generateZoomToken()
    {
//        base64_encode("Ta5ufGd1R9GIfRmMt72jQ:8DiDj0wmVrb3IROIWZezfF5hkf7zyBTX");

//        $base_key = base64_encode('PeyPdg6QZ6qJMbfF7zQ:WdsABtUFWmmoMnFJxePsUWPPZH39T8Wz');


        $response = Http::withHeaders([
            'Authorization' => 'Basic UGV5UGRnNlFaNnFKTWJmRjd6UTpXZHNBQnRVRldtbW9NbkZKeGVQc1VXUFBaSDM5VDhXeg==',
            'Host' => 'zoom.us',
            'Content-Type' => 'application/x-www-form-urlencoded', // Add this line to set the Content-Type
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => '5RhERupfR76wXMjTxGdqRg',
        ]);
       $data =  $response->json();
       return $data['access_token'];
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL', '');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {

            return Carbon::parse($dateTime,'UTC')->toDateTimeLocalString();
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : '.$e->getMessage());

            return '';
        }
    }

    public function create($data, $imam_id)
    {
        $url = $this->retrieveZoomUrl();
        $url = $url.'users/me/meetings';
        $this->access_token = $this->generateZoomToken();
        $hostEmail = User::find($imam_id);

        $body = [
                'topic'      => $data['topic'],
                'type'       => 2,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => 15,
                'agenda'     => 'Nikah Meeting',
                'timezone'     => $data['timezone'],
                "timeout" =>    1*10000,
                'host_email' => $hostEmail->email,
                'settings'   => [
                    'host_video'        => ($data['host_video'] == "1") ? true : false,
                    'participant_video' => ($data['participant_video'] == "1") ? true : false,
                    'approval_type' => 2, // Automatically approve all participants
                    'audio' => 'both',
                    'join_before_host' => true,
                    'mute_upon_entry' => true,
                    'waiting_room' => false,
                    'host_email' => $hostEmail->email,
                ],

        ];

//        dd($body,$this->access_token);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->access_token,
            'Content-Type' => 'application/json',
        ])->post('https://api.zoom.us/v2/users/me/meetings', $body);
//        dd($response,$response->json(), $body);
        return [
            'status' => $response->status(),
            'data'    => $response,
        ];
    }
}
