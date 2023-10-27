<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\Facades\Api;
use Exception;
use Illuminate\Http\Request;
use Pusher\Pusher;

class PusherController extends Controller
{
    public function auth_pusher(Request $request, $user_id)
    {
        try {
            $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), ['cluster' => env('PUSHER_APP_CLUSTER')]);
            return $pusher->authorizeChannel($request->get('channel_name'), $request->get('socket_id'));
        } catch (Exception $e) {
            return Api::server_error($e);
        }
    }
}
