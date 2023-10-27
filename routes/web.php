<?php
//phpinfo();die;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Api\NikahController;
use App\Http\Controllers\ImamDashboardController;
use App\Http\Controllers\ImamSchedularController;
use App\Http\Controllers\Web\Admin\ImamManagementController;
use App\Http\Controllers\Web\Admin\NikahManagementController;
use App\Http\Controllers\Web\Admin\WitnessManagementController;
use App\Http\Controllers\Web\Admin\SettingsController;
use App\Http\Controllers\Web\Imam\ImamNikahController;
use App\Http\Controllers\Web\Admin\TalaqController;
use App\Http\Controllers\Web\Admin\KhuluController;
use App\Http\Controllers\Web\Admin\RujuController;
use App\Http\Controllers\Web\Imam\ImamKhulaManagementController;
use App\Http\Controllers\Web\Admin\ChatController;
use App\Http\Controllers\HomeController;
use Pusher\Pusher;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\Web\Admin\UserController;
use \Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });



Route::get('/', function () {
/*$desiredDatePkt = '2023-09-12'; // User-provided date in PKT
$userTimeZone = 'Asia/Karachi'; // User's timezone
$databaseTimezone = 'UTC'; // Database timezone// Convert the user-provided PKT date to UTC
    $desiredDateUtc = \Carbon\Carbon::createFromFormat('Y-m-d', $desiredDatePkt, $userTimeZone)
        ->setTimezone($databaseTimezone)->format('Y-m-d');
    $results = DB::table('time_table_slots')
        ->selectRaw('
        start_time as original_start_time,
        end_time as original_end_time,
        CONVERT_TZ(start_time, "Asia/Karachi", "UTC") as converted_start_time,
        CONVERT_TZ(end_time, "Asia/Karachi", "UTC") as converted_end_time
    ')->get();
    dd($results);*/
    return Inertia::render('landing-page/index');
})->name('/');

Route::post('save-contacts', [HomeController::class, 'storeContact'])->name('storeContact');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('termsAndConditions');
Route::post('/notify-me', [HomeController::class, 'notifyMe'])->name('notifyMe');

Route::get('/#/{nav_id}', function () {
    return Inertia::render('landing-page/index');
})->name('navParts');


Route::get('/newpassword', function () {
    return Inertia::render('Auth/ResetPassword');
})->name('newpassword');

/* Admin Route */
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('nikahmanagement', [NikahManagementController::class, 'index'])->name('nikahmanagement');
    Route::get('nikahdetails/{nikah_id}', [NikahManagementController::class, 'detail'])->name('nikahdetails');
    Route::post('get-available-imams', [NikahManagementController::class, 'getImamsforAssiging'])->name('get-available-imams');
    Route::post('assign-imam', [NikahManagementController::class, 'assignImamToNikah'])->name('assign-imam');
    Route::post('get-witness-to-assign', [NikahManagementController::class, 'getWitnessToAssign'])->name('get-witness-to-assign');
    Route::post('assign-witness', [NikahManagementController::class, 'assignWitnessToNikah'])->name('assign-witness');
    Route::get('eventscalendar', [NikahManagementController::class, 'eventListings'])->name('eventscalendar');


    Route::get('storeVat', [SettingsController::class, 'storeVat'])->name('vat');
    Route::get('getPrivacyPolicy', [SettingsController::class, 'getPrivacyPolicy'])->name('getPrivacyPolicy');
    Route::post('storePrivacyPolicy', [SettingsController::class, 'privacyPolicy'])->name('storePrivacyPolicy');

    Route::get('witnesses', [WitnessManagementController::class, 'index'])->name('witness.index');
    Route::post('store-witness', [WitnessManagementController::class, 'store'])->name('witness.store');
    Route::post('delete-witness', [WitnessManagementController::class, 'deleteWitness'])->name('witness.delete');

    Route::get('imams', [ImamManagementController::class, 'getImams'])->name('imams');
    Route::post('change-status', [ImamManagementController::class, 'changeImamStatus'])->name('changeImamStatus');
    Route::post('delete-imam', [ImamManagementController::class, 'delete'])->name('delete-imam');

    Route::get('talaqs', [TalaqController::class, 'index'])->name('talaq');
    Route::get('khulas', [KhuluController::class, 'index'])->name('khula');
    Route::post('active-imams', [KhuluController::class, 'getAllActiveImams'])->name('getAllActiveImams');
    Route::post('assign-imam-to-khulu', [KhuluController::class, 'assignImamToKhulu'])->name('assignImamToKhulu');
    Route::get('ruju', [RujuController::class, 'index'])->name('ruju');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/chat-detail/{id}', [ChatController::class, 'chatDetail'])->name('chat-detail');
    Route::post('post-reply', [ChatController::class, 'postAdminReply'])->name('post-reply');
    Route::post('get-users-for-chat', [ChatController::class, 'getAllUsersForChat'])->name('get-users-for-chat');
    Route::post('get-latest-chat', [ChatController::class, 'getLatestChats'])->name('get-latest-chat');
    Route::get('contact-users-listing', [HomeController::class, 'getContactUsers'])->name('getContactUsers');
    Route::get('services-offered', [SettingsController::class, 'servicesOffered'])->name('servicesOffered');
    Route::post('update-service-price', [SettingsController::class, 'updateServicePrice'])->name('updateServicePrice');
    Route::get('nikah-types', [SettingsController::class, 'nikahTypes'])->name('nikahTypes');
    Route::post('udpate-nikah-type', [SettingsController::class, 'updateNikahType'])->name('updateNikahType');

    Route::get('users',[UserController::class,'index'])->name('users');

});

/* Imam Route */

Route::prefix('imam')->middleware(['auth', 'role:imam'])->group(function () {

    Route::get('/nikahmanagement', [ImamNikahController::class, 'index'])->middleware(['auth'])->name('imam.nikahmanagement');
    Route::get('/nikahdetails/{nikah_id}', [ImamNikahController::class, 'detail'])->middleware(['auth'])->name('imam.nikahdetails');
    Route::post('/validate-nikah', [ImamNikahController::class, 'validateNikah'])->middleware(['auth'])->name('imam.validateNikah');
    Route::post('/upload-certificate', [ImamNikahController::class, 'uploadCertificates'])->middleware(['auth'])->name('imam.uploadCertificates');
    Route::post('/store-recorded-link', [ImamNikahController::class, 'storeRecordedLink'])->middleware(['auth'])->name('imam.storeRecordedLink');

    Route::get('/event-scheduler', [ImamSchedularController::class, 'index'])->name('imam.event-scheduler');
    Route::get('/create-scheduler', [ImamSchedularController::class, 'create'])->name('imam.create-scheduler');
    Route::post('/store-timetable', [ImamSchedularController::class, 'saveTimeTable'])->name('imam.store-timetable');

    Route::get('khulu', [ImamKhulaManagementController::class, 'index'])->name('imam.khula');
    Route::post('validate-khulu', [ImamKhulaManagementController::class, 'validateKhulu'])->name('imam.validateKhulu');
    Route::post('reject-khulu', [ImamKhulaManagementController::class, 'rejectKhulu'])->name('imam.rejectKhulu');
    Route::post('delete-schedule-date',[ImamSchedularController::class,'deleteScheduleDate'])->name('deleteScheduleDate');
});


Route::middleware(['auth', RoleMiddleware::class . ':admin,imam'])->group(function () {
    Route::post('update-profile', [HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('update-password',[HomeController::class,'updatePassword'])->name('updatePassword');
});

Route::middleware(['auth', 'role:admin|imam'])->group(function () {
    Route::get('profile',[HomeController::class,'profile'])->name('profile');
//    Route::get('profile',[HomeController::class,'profile'])->name('admin.profile');
    Route::post('update-profile', [HomeController::class, 'updateProfile'])->name('updateProfile');
    Route::post('update-password', [HomeController::class, 'updatePassword'])->name('updatePassword');
});


require __DIR__ . '/auth.php';

Route::get('/qr-link-partner/{code}', function () {
    return '';
})->name('qr_link');


Route::post('stripe-webhook', [NikahController::class, 'webHook']);
Route::post('khula-webhook', [KhuluController::class, 'khulaWebHook']);
Route::get('/payment-complete', function () {
    return view('success');
});

Route::get('/payment-failed', function () {
    return view('failure');
});

Route::post('pusher/auth', function (\Illuminate\Http\Request $request) {
    try {

        $app_id = env('PUSHER_APP_ID');
        $app_key = env('PUSHER_APP_KEY');
        $app_secret = env('PUSHER_APP_SECRET');
        $app_cluster = env('PUSHER_APP_CLUSTER');

        $pusher = new Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);


        $response = $pusher->socket_auth($request->channel_name, $request->socket_id);
        return $response;
    } catch (Exception $e) {
        return response()->json($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
    }
})->name('pusher-auth');



Route::get('db-roll',function (){
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('wallis')->truncate();
    DB::table('wakeels')->truncate();
    DB::table('witness')->truncate();
    DB::table('talaqs')->truncate();
    DB::table('rujus')->truncate();
    DB::table('pregnancy_details')->truncate();
    DB::table('social_users')->truncate();
    DB::table('service_obtaineds')->truncate();
    DB::table('payments')->truncate();
    DB::table('partner_details')->truncate();
    DB::table('otps')->truncate();
    DB::table('notifications')->truncate();
    DB::table('nikah_time_table')->truncate();
    DB::table('time_table_slots')->truncate();
    DB::table('nikah_drafts')->truncate();
    DB::table('nikah_detail_histories')->truncate();
    DB::table('nikahs')->truncate();
    DB::table('certificates')->truncate();
    DB::table('chats')->truncate();
    DB::table('chat_users')->truncate();
    DB::table('contact_emails')->truncate();
    DB::table('failed_jobs')->truncate();
    DB::table('jobs')->truncate();
    DB::table('khulus')->truncate();
    DB::table('messages')->truncate();
    DB::table('message_files')->truncate();
    DB::table('users')->whereNotIn('id', [1,571])->delete();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
})->middleware('auth.basic');;
