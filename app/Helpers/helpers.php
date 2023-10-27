<?php

use App\Models\OTP;
use App\Models\PortalSetting;
use App\Models\User;
use App\Notifications\InviteNotification;
use App\Notifications\OTPEmail;
use App\Notifications\OTPPhone;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Twilio\Rest\Client;

if (!function_exists('generate_qr_image')) {
    function generate_qr_image($number)
    {
//        dd($number);
        $renderer = new BaconQrCode\Renderer\ImageRenderer(
            new BaconQrCode\Renderer\RendererStyle\RendererStyle(800),
            new BaconQrCode\Renderer\Image\ImagickImageBackEnd()
        );
//        dd($renderer);

        $writer = new BaconQrCode\Writer($renderer);
        $writer->writeFile(route('qr_link', $number), public_path('files/qrcodes/') . $number . '.png');
    }
}

if (!function_exists('generate_code')) {
    function generate_code($length): string
    {
        $code = array_merge(range(0, 9), range(0, 9));
        shuffle($code);
        return implode(array_slice($code, 0, $length));
    }
}

if (!function_exists('generate_filename')) {
    function generate_filename($file): string
    {
//        if($file->getClientOriginalExtension() == 'MP4'){
        return Str::random() . '.' . time() . '.' . $file->getClientOriginalExtension();
//        }
//        return $file->getClientOriginalName();
    }
}

if (!function_exists('send_otp')) {
    function send_otp($user, $type): string
    {
        $code_number = generate_code(4);


        if ($type == 'email') {
            $check_user_otp = OTP::where('slug', $type)->where('value', $user->email)->first();
            if ($check_user_otp) {
                $check_user_otp->otp = $code_number;
                $check_user_otp->update();
            } else {
                $check_user_otp = OTP::create([
                    'slug' => $type,
                    'value' => $user->email,
                    'otp' => $code_number
                ]);
            }
            $user->notify(new OTPEmail($code_number));
        } elseif ($type == 'phone') {
            $check_user_otp = OTP::where('slug', $type)->where('value', $user->phone)->first();
            if ($check_user_otp) {
                $check_user_otp->otp = $code_number;
                $check_user_otp->update();
            } else {
                $check_user_otp = OTP::create([
                    'slug' => 'phone',
                    'value' => $user->phone,
                    'otp' => $code_number
                ]);
            }
            $user->notify(new OTPPhone($code_number));
        }
        return $code_number;
    }
}

if (!function_exists('imagePath')) {
    function imagePath($image, $type): string
    {
        if ($image == '') {
            return 'null';
        }
        if ($type == 'selfie') {
            $image = asset('storage/' . $image);
        } else if ($type == 'profile_image') {
            $image = asset('storage/' . $image);
        } else if ($type == 'id_card_front') {
            $image = asset('storage/' . $image);
        } else if ($type == 'other') {
            $image = asset('storage/' . $image);
        }
        return $image;
    }
}


if (!function_exists('checkGender')) {
    function checkGender($user): string
    {
        $gender = "";
        if (strtolower($user->gender) == 'male') {
            $gender = "male";
        }
        if (strtolower($user->gender) == 'female') {
            $gender = "female";
        }
        return $gender;
    }
}
if (!function_exists('rand_time')) {

    function rand_time($min_date, $max_date)
    {
        /* Gets 2 dates as string, earlier and later date.
           Returns date in between them.
        */

        $min_epoch = strtotime($min_date);
        $max_epoch = strtotime($max_date);

        $rand_epoch = rand($min_epoch, $max_epoch);

        return date('H:i:s', $rand_epoch);
    }

}
if (!function_exists('formatNumbers')) {
    function formatNumbers($number)
    {
        $result = number_format($number, 2);
        return (float)$result;
    }
}

if (!function_exists('requiredUsersEmail')) {
    function requiredUsersEmail($service_id)
    {
        $required_email = 0;
        if ($service_id == 7 || $service_id == 11) {
            $required_email = 1;
        }
        return $required_email;
    }
}

if (!function_exists('errorMessage')) {
    function errorMessage($message = null, $error = true, $status = 422)
    {
        $return_array['message'] = $message;
        $return_array['error'] = $error;
        $return_array['status'] = $status;
        return $return_array;
    }
}
if (!function_exists('successResponse')) {
    function successResponse($data = null, $message = null, $error = false, $status = 200)
    {
        $return_array['data'] = $data;
        $return_array['message'] = $message;
        $return_array['error'] = $error;
        $return_array['status'] = $status;
        return $return_array;
    }
}
if (!function_exists('fullName')) {
    function fullName($first_name, $last_name)
    {
        return ucfirst($first_name . ' ' . $last_name);
    }
}

if (!function_exists('checkIfUploadedFileHasSameName')) {
    function checkIfUploadedFileHasSameName($imagePath)
    {
        if (!is_null($imagePath)) {
            return file_exists(public_path($imagePath));
        }
        return false;
    }
}

if (!function_exists('checkIfFileIsUploadedThenDelete')) {
    function checkIfFileIsUploadedThenDelete($imagePath)
    {
        if (!is_null($imagePath)) {
            \Illuminate\Support\Facades\File::delete(public_path($imagePath));
            return true;
        }
        return false;
    }
}

if (!function_exists('checkIfDirectoryIsAvailable')) {
    function checkIfDirectoryIsAvailable($directory)
    {
        if (!\Illuminate\Support\Facades\File::exists(public_path($directory))) {
            \Illuminate\Support\Facades\File::makeDirectory(public_path($directory), 0777, true);
        }
    }
}
if (!function_exists('includeVatInPrice')) {
    function includeVatInPrice($total_price)
    {
        $vat = PortalSetting::where('name', 'vat')->first();
        if ($vat) {
            $vat_percentage = ($total_price * $vat->value) / 100;
            $total_price = $total_price + $vat_percentage;
        }
        return $total_price;
    }
}

if (!function_exists('getChatNewId')) {
    function getChatNewId($user = null)
    {
        /*  $chat = \Illuminate\Support\Facades\DB::table('chat_users')->where('user_id',$user->id)->value('chat_id');
          if(empty($chat)){
              $data = [];
              $chat = \App\Models\Chat::insertGetId($data);
          }
          return $chat;*/
        $table = 'chats';
//        $result = \Illuminate\Support\Facades\DB::select("SHOW TABLE STATUS LIKE '{$table}'");
        $sql = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE $table")[0]->{'Create Table'};
        preg_match('/AUTO_INCREMENT=(\d+)/', $sql, $matches);
        $autoIncrement = (int)$matches[1];
//        dd($autoIncrement);
        /*    $query = "SELECT AUTO_INCREMENT + 1
                        FROM information_schema.TABLES
                        WHERE TABLE_SCHEMA = 'nikah_admin'
                        AND TABLE_NAME = 'chats'";*/
//        $result = \Illuminate\Support\Facades\DB::raw($query);
//        dd($result);
//        $nextAutoIncrementId = $result[0]->Auto_increment;
//        dd($nextAutoIncrementId);
        return $autoIncrement;
    }
}


if (!function_exists('serverError')) {
    function serverError(\Throwable $throwable)
    {
        $code = $throwable->getCode() ?? 500;
        $code = $code > 0 ? $code : 500;
        return config('app.debug') ? $throwable->getMessage() : trans('response.server_error');
    }
}

function getAdminUsersIDs()
{
    return User::whereHas('roles', function ($q) {
        $q->where('name', 'admin');
    })->get()->pluck('id')->toArray();
}

function chatUsers($chat_id)
{
    return DB::table('chat_users')
        ->where('chat_users.chat_id', $chat_id)
        ->distinct()
        ->pluck('chat_users.user_id')
        ->toArray();
}

function getReceiver($chat_users)
{
    $receiver_id = array_diff($chat_users, getAdminUsersIDs());
    $receiver = User::find(current($receiver_id));
    return $receiver;
}

function twillioSend($request, $code_number)
{
    $client = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));
    if($request->country_code == '+1'){
        $from_number = config('services.twilio.us_number');
    }else{
        $from_number = config('services.twilio.number');
    }
    $client->messages->create($request->country_code . '' . $request->phone, ['from' => $from_number, 'body' => "Your OTP code is " . $code_number]);
}


function sendInviteToWitness($witneess_email, $nikah, $witness_user,$password)
{
    $type = 'Witness';

    \Illuminate\Support\Facades\Log::info(['Witness arguments' => [$witneess_email,$password,$nikah,$type]]);
    $witness_user->notify(new InviteNotification($witneess_email,$password,$nikah ,'Witness'));
}

function sendInviteToWalli($wali_email, $nikah, $wali_user, $password)
{
    $type = 'Wali';
    \Illuminate\Support\Facades\Log::info(['wali arguments' => [$wali_email,$password,$nikah,$type]]);
    $wali_user->notify(new InviteNotification($wali_email,$password,$nikah,$type));
}


function generateStrongPassword()
{
    // Characters for the password
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    // Generate an 8-character random alphanumeric password
    $password = Str::random(8, $characters);

    return $password;
}


if (!function_exists('timeDiff')) {
    function timeDiff($date_time, $from, $timezones)
    {
        //given time e.g. 5pm will be considered as the time of first timezone
        //and this time will be shifted to other timezones.
        //timeDiff(now(),'UTC',['EST','US/Eastern'])
        $data = [];
        $timezones[] = 'utc';
        $firstTimezone = $from;
        foreach ($timezones as $timezone) {
            $data[$timezone] = Carbon::parse($date_time)->shiftTimezone($firstTimezone)->setTimezone($timezone)->format('M j, Y g:i A');
        }
        return $data;
    }
}

if (!function_exists('changeDatetimeZone')) {
    function changeDatetimeZone($date, $userTimeZone, $databaseTimezone='UTC')
    {
        $desiredDateUtc = Carbon::createFromFormat('Y-m-d H:i:s', $date, $userTimeZone)
            ->setTimezone($databaseTimezone)
            ->format('Y-m-d H:i:s');
//        $results = \App\Models\TimeSlot::
//        whereDate('start_time','=',$desiredDateUtc)
//            ->get()->toArray();
//
//        foreach ($results as &$result){
//            $result['start_time_2'] = \Carbon\Carbon::parse($result['start_time'])->shiftTimezone($databaseTimezone)->setTimezone($userTimeZone)->toDateTimeString();
//        }
        return $desiredDateUtc;
    }
}

if (!function_exists('setImamSlots')) {
    function setImamSlots()
    {
        $imams = \App\Service\ImamService::Imams();
        foreach ($imams as $imam){
            $set_slots = \App\Service\NikahRelatedService::makeSlotsForImam($imam->id);
        }
        return true;
    }
}


function sendNotificationTest($country_code, $phone, $code_number)
{
    $request =[
        'country_code' => '+1',
        'phone' => '5853121741'
    ];
    $client = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));
    if($country_code == '+1'){
        $from_number = config('services.twilio.us_number');
    }else{
        $from_number = config('services.twilio.number');
    }
    $message = $client->messages->create($country_code . '' . $phone, ['from' => $from_number, 'body' => "Your OTP code is " . $code_number]);
    return ['body' => $message->body, 'response' => $message->errorMessage, 'status' => $message->status, 'res' => $message->toArray()];
}




