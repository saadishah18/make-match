<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KhuluController;
use App\Http\Controllers\Api\MyActivityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\PusherController;
use App\Http\Controllers\Api\RujuController;
use App\Http\Controllers\Api\SupportChatController;
use App\Http\Controllers\Api\TalaqController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashbboardController;
use App\Http\Controllers\Api\NikahTypeController;
use App\Http\Controllers\Api\NikahController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\MyServicesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('social-login', [AuthController::class, 'social']);
Route::post('register', [AuthController::class, 'register']);
Route::post('phone/send-otp', [AuthController::class, 'sendPhoneOTP']);
Route::post('email/send-email-otp', [AuthController::class, 'sendEmailOTP']);
Route::post('phone/verify', [AuthController::class, 'verifyPhone']);
Route::post('email/verify-email-otp', [AuthController::class, 'verifyEmailOTP']);
Route::post('forgot-password/send-opt', [AuthController::class, 'sendForgotPasswordOTP']);
Route::post('forgot-password/update-password', [AuthController::class, 'forgotPasswordVerifyOTP']);
Route::post('authorize/pusher/{user_id}', [PusherController::class, 'auth_pusher']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('update-device-token', [AuthController::class, 'updateDeviceToken']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::put('profile', [ProfileController::class, 'update']);
    Route::patch('profile', [ProfileController::class, 'update']);
    Route::get('profile', [ProfileController::class, 'get_info']);
    Route::get('qr-code', [ProfileController::class, 'userQrCode']);
    Route::post('link-partner', [ProfileController::class, 'linkPartner']);
    Route::get('user-transactions', [ProfileController::class, 'getTransactionDetail']);
    Route::get('certificates', [ProfileController::class, 'certificates']);
    Route::get('delete-account', [ProfileController::class, 'deleteAccount']);

//    Route::get('support_chat/{user_id}', [SupportChatController::class, 'index']);
    Route::get('support_chat', [SupportChatController::class, 'index']);
    Route::post('support_chat', [SupportChatController::class, 'store']);

    Route::get('dashboard',[DashbboardController::class,'dashboardRecords']);

    Route::get('nikah-types',[NikahTypeController::class,'index']);
    Route::post('nikah-services',[NikahTypeController::class,'services']);
    Route::post('calendar-dates',[NikahController::class,'calendarDates']);
    Route::post('date-slots',[NikahController::class,'getDateSlots']);
    Route::post('save-draft-nikah',[NikahController::class,'saveNikahAsDraft']);
    Route::post('save-nikah',[NikahController::class,'saveNikKah']);
    Route::post('resend-invitation',[NikahController::class,'resendInvitation']);
//    Route::get('stripe-token',[StripeController::class,'getToken']);
//    Route::get('stripe-link',[StripeController::class,'makeLink']);
//    Route::get('stripe-customer',[StripeController::class,'getCustomer']);
    Route::get('user-own-services',[MyServicesController::class,'userOwnServices']);
    Route::get('my-activities',[MyActivityController::class,'index']);
    Route::post('accept-invitation',[MyActivityController::class,'acceptInvitation']);
    Route::post('apply-talaq',[TalaqController::class,'applyTalaq']);
    Route::post('add-pregnancy-detail',[TalaqController::class,'addPregnancyDetail']);
    Route::post('apply-ruju',[RujuController::class,'applyRuju']);
    Route::post('accept-ruju',[RujuController::class,'acceptRujuRequest']);
    Route::post('reject-ruju',[RujuController::class,'rejectRujuRequest']);
    Route::post('apply-khulu',[KhuluController::class,'applyKhulu']);
    Route::post('accept-khulu',[KhuluController::class,'acceptKhuluRequest']);
    Route::post('reject-khulu',[KhuluController::class,'rejectKhuluRequest']);

});
