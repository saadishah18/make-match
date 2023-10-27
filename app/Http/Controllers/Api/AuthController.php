<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\UserResource;
use App\Mail\NotifyMe;
use App\Mail\WelcomeMail;
use App\Models\OTP;
use App\Models\Plans;
use App\Models\SocialUser;
use App\Models\User;
use App\Notifications\OTPEmail;
use App\Notifications\OTPPhone;
use App\Service\Facades\Api;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['email' => 'required|email', 'password' => 'required'])) {
                return Api::validation_errors();
            }

            $user = User::firstWhere('email', $request->email);
            if (!$user) {
                return Api::error(trans('auth.failed'));
            }
            if (!Hash::check($request->password, $user->password)) {
                return Api::error(trans('auth.password'));
            }
            return Api::response([
                'user' => new UserResource($user),
//                'partner_detail' => $this->userAsPartnerData != null ? new PartnerResource($user) : null,
                'access_token' => $user->createToken('AccessToken')->plainTextToken
            ]);
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }

    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:6',
                'country_code' => 'required',
                'phone' =>'required|unique:users'
            ])) {
                return Api::validation_errors();
            }
            $user = User::create([
                'email' => $request->email,
                'qr_number' => User::generateQRNumber(),
                'password' => Hash::make($request->password),
                'country_code' =>  $request->country_code,
                'country_name' =>  $request->country_name,
                'phone' =>  $request->phone,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
            $role = $user->assignrole('user');
           $mail  = Mail::to($request['email'])->send(new WelcomeMail($user));
//           $mail2 =  Mail::to($user->email)->send(new WelcomeMail($user));
            return Api::response([
                'user' => new UserResource($user),
                'access_token' => $user->createToken('AccessToken')->plainTextToken
            ]);
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function social(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate([
                'email' => 'required',
                'provider_id' => 'required',
                'provider' => 'required|in:facebook,google,apple',
//                'first_name' => 'required',
//                'last_name' => 'required',
            ])) {
                return Api::validation_errors();
            }
            $social_user = SocialUser::firstWhere($request->only(['provider', 'provider_id']));
            if (!$social_user) {
                $email = $request->has('email') ? $request->email : $request->provider_id . '@' . strtolower($request->provider) . '.com';
                if (!Api::validate([
                    'email' => 'required|email|unique:users',

                ])) {
                    return Api::validation_errors();
                }
                $user = User::create([
                    'email' => $email,
                    'qr_number' => User::generateQRNumber(),
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                ]);
                $social_user = SocialUser::create([
                    'user_id' => $user->id,
                    'provider_id' => $request->provider_id,
                    'provider' => $request->provider,
                ]);
                $mail  = Mail::to($request['email'])->send(new WelcomeMail($user));

                return Api::response([
                    'user' => new UserResource($user),
                    'access_token' => $user->createToken('AccessToken')->plainTextToken
                ]);
            } else {
                if($social_user->user != null){
                    $email = $request->has('email') ? $request->email : $request->provider_id . '@' . strtolower($request->provider) . '.com';
//                    $email = $social_user->user->email;
                    $user = User::where('id',$social_user->user->id)->first();
                    if($user == null){
                        $user = User::create([
                            'email' => $email,
                            'qr_number' => User::generateQRNumber(),
                            'first_name' => $request['first_name'],
                            'last_name' => $request['last_name'],
                        ]);
                    }
                }
                return Api::response([
                    'user' => new UserResource($user),
                    'access_token' => $user->createToken('AccessToken')->plainTextToken
                ]);
            }
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function sendForgotPasswordOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['email' => 'required'])) {
                return Api::validation_errors();
            }
            $user = User::firstWhere($request->only('email'));
            if (!$user) {
                return Api::error(trans('auth.failed'));
            }

            $code_number = generate_code(4);
            $check_user_otp = $this->checkOTPExits($code_number, 'email',$request->email);

            $user->notify(new OTPEmail($code_number));
            return Api::response(data: ['opt_code' => $code_number], message: trans('auth.otp_sent', ['digit' => 4, 'medium' => 'email']));
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function forgotPasswordVerifyOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['phone' => 'required', 'password' => 'required|confirmed|min:6'])) {
                return Api::validation_errors();
            }

            $user = User::where(DB::raw("CONCAT(country_code,phone )"), $request->only('phone'))->first();;
            if (!$user) {
                return Api::error(trans('auth.failed'));
            }

//            if (!OTP::where(['slug' => 'email', 'value' => $user->email, 'otp' => $request->otp])->count()) {
//                return Api::error(trans('auth.otp_failed'));
//            }

            $user->update(['password' => bcrypt($request->password)]);

            return Api::response(message: trans('auth.password_updated'));
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function sendPhoneOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['phone' => 'required'])) {
                return Api::validation_errors();
            }
            $code_number = generate_code(4);

//            $user = User::firstWhere($request->only('phone'));
            $this->checkOTPExits($code_number, 'phone',$request->phone);

            $user = User::where(DB::raw("CONCAT(country_code,phone )"), $request->only('phone'))->first();
            if (!$user) {
                twillioSend($request,$code_number);
                return Api::response(data: ['otp_code' => $code_number],message: trans('auth.otp_sent', ['medium' => 'phone', 'digit' => 4]));
            }

            $user->notify(new OTPPhone($code_number));
            return Api::response(data: ['otp_code' => $code_number],message: trans('auth.otp_sent', ['medium' => 'phone', 'digit' => 4]));
        } catch (\Exception $exception) {
            if($exception->getCode() == 21211){
                User::where(DB::raw("CONCAT(country_code,phone )"), $request->only('phone'))->forceDelete();
                return Api::error('Phone number is invalid');
            }
            return Api::server_error($exception);
        }
    }

    public function verifyPhone(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['phone' => 'required', 'otp' => 'required'])) {
                return Api::validation_errors();
            }

            $user = User::where(DB::raw("CONCAT(country_code,phone )"), $request->only('phone'))->first();;
            if (!$user) {
//                return Api::error(trans('auth.failed'));
                if (OTP::where(['slug' => 'phone', 'value' => $request->phone, 'otp' => $request->otp])->count()) {
                    return Api::response(message: trans('auth.phone_verified'));
                }else{
                    return Api::error(trans('auth.otp_failed'));
                }
            }
            if (!OTP::where(['slug' => 'phone', 'value' => $request->phone, 'otp' => $request->otp])->count()) {
                return Api::error(trans('auth.otp_failed'));
            }

            $user->update(['phone_verified_at' => now()->toDateTimeString()]);
            return Api::response(message: trans('auth.phone_verified'));
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function sendEmailOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['email' => 'required'])) {
                return Api::validation_errors();
            }

            $user = User::firstWhere($request->only('email'));
            if (!$user) {
                return Api::not_found();
            }
            $code_number = generate_code(4);
            $check_user_otp = $this->checkOTPExits($code_number, 'email',$request->email);
            $user->notify(new OTPEmail($code_number));
            return Api::response(data: ['opt_code' => $code_number],message: trans('auth.otp_sent', ['medium' => 'phone', 'digit' => 4]));
        } catch (\Exception $exception) {
//            dd($exception->getMessage(), $exception->getLine(), $exception->getFile(), $exception->getTrace());
            return Api::server_error($exception);
        }
    }

    public function verifyEmailOTP(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['email' => 'required', 'otp' => 'required'])) {
                return Api::validation_errors();
            }

            $user = User::firstWhere($request->only('email'));
            if (!$user) {
                return Api::error(trans('auth.failed'));
            }

            if (!OTP::where(['slug' => 'email', 'value' => $user->email, 'otp' => $request->otp])->count()) {
                return Api::error(trans('auth.otp_failed'));
            }

            if($user->email_verified_at != null){
                $user->update(['email_verified_at' => now()->toDateTimeString()]);
            }
            return Api::response(message: trans('auth.email_verified'));
        } catch (\Exception $exception) {
//            dd($exception->getMessage());
            return Api::server_error($exception);
        }
    }

    public function updateDeviceToken(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate(['token' => 'required'])) {
                return Api::validation_errors();
            }
            auth()->user()->update([
                'device_token' => $request->token
            ]);
            return Api::response(message: trans('auth.token_update'));
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function checkOTPExits($code, $type, $value){
        $check_user_otp = OTP::where('slug',$type)->where('value',$value)->first();
        if($check_user_otp){
            $check_user_otp->otp = $code;
            $check_user_otp->update();
        }else{
            $check_user_otp = OTP::create([
                'slug' => $type,
                'value' => $value,
                'otp' => $code
            ]);
        }
        return $check_user_otp;
    }

    public function logout(){
        $user = auth()->user();
        $user->device_token = null;
        $user->update();
        auth()->user()->tokens()->delete();
        return Api::response(message: trans('auth.logout'));
    }
}
