<?php

namespace App\Repositories;

use App\Http\Resources\NikahResource;
use App\Library\Facade\ApiResponse;
use App\Mail\NikahMail;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahDraft;
use App\Models\NikahTimeTable;
use App\Models\NikahType;
use App\Models\PartnerDetail;
use App\Models\Payments;
use App\Models\PortalSetting;
use App\Models\ServiceObtained;
use App\Models\Services;
use App\Models\User;
use App\Models\Walli;
use App\Models\Witness;
use App\Notifications\InviteNotification;
use App\Repositories\Interfaces\NikahInterface;
use App\Service\Facades\Api;
use App\Service\NikahRelatedService;
use App\Traits\NikahTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

//use Stripe\Stripe;
//use Cartalyst\Stripe\Stripe;
class NikahRepository implements NikahInterface
{
    use NikahTrait;

    public function nikahTypes()
    {
        $types = NikahType::all();
        return $types;
    }

    public function nikahServices($request)
    {
        if (!Api::validate(['type_id' => 'required|integer'])) {
            $result_array['error'] = true;
            return $result_array;
        }

        $services = Services::all();
        $result_array = [];
        foreach ($services as $key => $data) {
//            dd($data->price);
            if ($key < 2) {
                $result_array['print_services'][] = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'price' => formatNumbers($data->price),
                    'is_mutiple' => 1,
                    'email_required' => $data->email_required,
                    'slug' => $data->slug,
                    'description' => $data->description
                ];
            }
            if ($key >= 2 && $key < 4) {
                $result_array['wali_services'][] = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'price' => formatNumbers($data->price),
                    'is_mutiple' => 0,
                    'email_required' => $data->email_required,
                    'slug' => $data->slug,
                    'description' => $data->description

                ];
            }
            if ($key >= 4 && $key < 6) {
                $result_array['witnesses'][] = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'price' => formatNumbers($data->price),
                    'is_mutiple' => $data->email_required,
                    'slug' => $data->slug,
                    'description' => $data->description

                ];
            }
            if ($key > 6) {
                $result_array['Imam'][] = [
                    'id' => $data->id,
                    'name' => $data->name,
                    'price' => formatNumbers($data->price),
                    'is_mutiple' => $data->email_required,
                    'slug' => $data->slug,
                    'description' => $data->description

                ];
            }
        }
        return $result_array;
    }

    public function calendarDates($request)
    {
        $validation = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'type_id' => 'required|integer|in:1,2',
            'time_zone' => 'required'
        ]);
        if ($validation->fails()) {
            return errorMessage($validation->errors()->first(), true, 422);
        }
        $get_available_dates_from_settings = NikahRelatedService::getAvailableDatesOfAllImams($request->start_date, $request->end_date, $request->type_id, $request->time_zone);
        if (is_array($get_available_dates_from_settings) && count($get_available_dates_from_settings)) {
            return successResponse(['available_dates' => $get_available_dates_from_settings], config('response.success'), false, 200);
        } else {
            return successResponse(['available_dates' => []], config('response.success'), false, 200);

        }
    }

    public function getDateSlots($request)
    {
        $validation = Validator::make($request->all(), [
            'nikah_date' => 'required|date',
            'time_zone' => 'required'
        ]);
        if ($validation->fails()) {
            $return_array['message'] = $validation->errors()->first();;
            $return_array['error'] = true;
            $return_array['status'] = 422;
            return $return_array;
        }

        $get_available_dates_from_settings = NikahRelatedService::getDateSlots($request->nikah_date, $request->time_zone);
        if (isset($get_available_dates_from_settings['error'])) {
            return $get_available_dates_from_settings;
        }
        if (is_array($get_available_dates_from_settings) && count($get_available_dates_from_settings)) {
            $return_array['data'] = $get_available_dates_from_settings;
            $return_array['message'] = config('response.success');
            $return_array['error'] = false;
            $return_array['status'] = 200;
            return $return_array;
        } else {
            $return_array['data'] = $get_available_dates_from_settings;
            $return_array['message'] = config('response.success');
            $return_array['error'] = false;
            $return_array['status'] = 200;
            return $return_array;

        }
    }

    public function saveNikahAsDraft($request)
    {
        $request_data = $request->all();

        $partners = [
            $request->user_applied_nikah_id,
            $request->partner_id
        ];
        $check_nikah_draft = NikahDraft::whereIn('user_id', $partners)
            ->whereIn('partner_id', $partners)->orderBy('created_at', 'desc')->first();

        if ($check_nikah_draft) {
            $minutesDifference = now()->diffInMinutes($check_nikah_draft->updated_at);
            if($minutesDifference < 2){
                return errorMessage('Either you or your partner already applied for Nikah. Please apply after 2 minutes.');
            }
        }

        array_push($request_data['services'], 'nikah_imam');
        $services_validation = $this->servicesValidationChecks($request_data);
        if ($services_validation != null && isset($services_validation['error']) && $services_validation['error']) {
            return $services_validation;
        };

        $checkPartnerExists = PartnerDetail::where('male_id', $request_data['user_applied_nikah_id'])->orWhere('female_id', $request_data['user_applied_nikah_id'])->first();

        if (empty($checkPartnerExists)) {
            return errorMessage('No partner with this user');
        }

        $applied_user = User::find($request_data['user_applied_nikah_id']);

        if (empty($applied_user)) return errorMessage('Applying User id is not correct');

        // check applied user previous nikah existence
        $nikah_history_applied_user = $this->checkNikahStatus($applied_user);

        if ($nikah_history_applied_user) {
            return errorMessage('You are already in a nikah with someone else! Current Status ' . ucfirst($nikah_history_applied_user['current_status']));
        }

        // check partner nikah existence
        $partner_user = User::find($request_data['partner_id']);

        if (empty($partner_user)) return errorMessage('Partner id is not correct');

        $nikah_history_partner_user = $this->checkNikahStatus($applied_user);

        if ($nikah_history_partner_user) {
            return errorMessage('Your partner already in a nikah with someone else! Current Status ' . ucfirst($nikah_history_partner_user['current_status']));
        }

        if (checkGender($applied_user) == checkGender($partner_user)) {
            return errorMessage('Same Gender can not apply');
        }
        /*if (checkGender($applied_user) == 'male' && checkGender($partner_user) == 'female') {
            $male_id = $applied_user->id;
            $female_id = $partner_user->id;
        }
        if (checkGender($applied_user) == 'female' && checkGender($partner_user) == 'male') {
            $female_id = $applied_user->id;
            $male_id = $partner_user->id;
        }*/

        $price_data = [
            'type_id' => $request_data['nikah_type_id'],
            'services' => $request_data['services'],
        ];

        $nikah_price = $this->calculateNikahPriceWithVat($price_data);

        $post_data = $request->all();
        if($check_nikah_draft){
            $check_nikah_draft->request_data = json_encode($post_data);
            $check_nikah_draft->user_id = $request_data['user_applied_nikah_id'];
            $check_nikah_draft->partner_id = $request_data['partner_id'];
            $check_nikah_draft->update();
            $post_data['draft_id'] = $check_nikah_draft->id;
        }else{
            $nikah_draft_object = new NikahDraft();
            $nikah_draft_object->request_data = json_encode($post_data);
            $nikah_draft_object->user_id = $request_data['user_applied_nikah_id'];
            $nikah_draft_object->partner_id = $request_data['partner_id'];
            $nikah_draft_object->save();
            $post_data['draft_id'] = $nikah_draft_object->id;
        }
        if($check_nikah_draft == null) {
            $payment_intent = $this->makeLink($nikah_price, $post_data);
            if($payment_intent === false){
//                return Api::error('Intent not created! Try again');
                return errorMessage('Intent not created! Try again');
            }
            $draft = NikahDraft::find($post_data['draft_id']);
            $draft->intent_generated = $payment_intent['id'];
            $draft->client_secret = $payment_intent['client_secret'];
            $draft->update();
            return $payment_intent;
        }else{
            $payment_intent = [
                'id' => $check_nikah_draft->intent_generated,
                'client_secret' => $check_nikah_draft->client_secret,
            ];
            return $payment_intent;
        }
    }

    public function calculateNikahPrice($price_data)
    {
        $type_detail = NikahType::find($price_data['type_id']);
        $type_price = $type_detail->price;
        $services_detail = Services::whereIN('slug', $price_data['services'])->get()->pluck('price')->toArray();
        $services_price = 0;
        foreach ($services_detail as $detail) {
            $services_price = $detail != null ? $services_price + $detail : $services_price + 0;
        }
        $total_price = $type_price + $services_price;
        return $total_price;
    }

    public function calculateNikahPriceWithVat($price_data)
    {
        $type_detail = NikahType::find($price_data['type_id']);
        $type_price = $type_detail->price;

        $services_detail = Services::whereIN('slug', $price_data['services'])->get()->pluck('price')->toArray();
        $services_price = 0;
        foreach ($services_detail as $detail) {
            $services_price = $detail != null ? $services_price + $detail : $services_price + 0;
        }
//        $imam_price = Services::where('slug','nikah_imam')->first()->price;

        $total_price = $type_price + $services_price;

        $vat = PortalSetting::where('name', 'vat')->first();

        if ($vat) {
            $vat_percentage = ($total_price * $vat->value) / 100;
            $total_price = $total_price + $vat_percentage;
        }
        return $total_price;
    }

    public function saveNikKah($data, $params)
    {
        return DB::transaction(function () use ($data, $params) {
            $request_data = (array)$data;

            $applied_user = User::find($request_data['user_applied_nikah_id']);
            $partner_user = User::find($request_data['partner_id']);
            if (checkGender($applied_user) == 'male' && checkGender($partner_user) == 'female') {
                $male_id = $applied_user->id;
                $female_id = $partner_user->id;
            }

            if (checkGender($applied_user) == 'female' && checkGender($partner_user) == 'male') {
                $female_id = $applied_user->id;
                $male_id = $partner_user->id;
            }

            $save_nikah = [
                'nikah_type_id' => $request_data['nikah_type_id'],
                'user_id' => $request_data['user_applied_nikah_id'],
                'partner_id' => $request_data['partner_id'],
                'nikah_date' => Carbon::parse($request_data['nikah_date'])->toDateString(),
                'start_time' => Carbon::parse($request_data['start_time'])->toTimeString(),
                'end_time' => Carbon::parse($request_data['end_time'])->toTimeString(),
            ];

            $nikah = Nikah::create($save_nikah);

            $nikah_history = NikahRelatedService::saveNikahHistory($nikah, $male_id, $female_id,);
            $services_data = NikahRelatedService::saveServiceObtainedInNikah($request_data, $nikah);
            array_push($request_data['services'], 'nikah_imam');

            $price_data = [
                'type_id' => $nikah->nikah_type_id,
                'services' => $request_data['services'],
            ];
            $nikah_service_price = $this->calculateNikahPrice($price_data);

            $nikah_price_with_vat = $this->calculateNikahPriceWithVat($price_data);

            $transactional_data = [
                'activity_id' => $nikah->id,
                'activity_name' => 'Nikah',
                'male_id' => $nikah_history->male_id,
                'female_id' => $nikah_history->female_id,
                'services_total_price' => formatNumbers($nikah_service_price),
                'total' => formatNumbers($nikah_price_with_vat),
                'transaction_id' => $params['transaction_id'],
                'paid_by_platform' => 'stripe',
                'status' => 'completed',
            ];
            $create_transaction = Payments::create($transactional_data);
            if ($create_transaction) {
                $partner = User::find($request_data['partner_id']);
                $requester = User::find($request_data['user_applied_nikah_id']);
                Mail::to($partner->email)->queue(new NikahMail($partner, $requester, $nikah));

            }

            $user = User::find($request_data['user_applied_nikah_id']);
            $current_nikah = Nikah::find($nikah->id);
            $current_nikah->timezone = $user->timezone;
            $current_nikah->save();

            return 'Transaction completed successfully';
        });
    }

    public function servicesValidationChecks($request_data)
    {
//        $array1 = ['print', 'video', 'certificate'];
        if (!in_array('nikah_with_wakeel', $request_data['services']) && !in_array('nikah_with_wali', $request_data['services'])) {
            return errorMessage('Please choose one option in Nikah with Wali Or Nikah with Wakil');
        }

        if (!in_array('own_witness', $request_data['services']) && !in_array('nikah_witness', $request_data['services'])) {
            return errorMessage('Please choose one option in Nikah provided Witnesses Or Your Own Witnesses');
        }

        foreach ($request_data['services'] as $index => $service) {
//             if ($index == 0) {
//                 if (!in_array($service, $array1)) {
//                     return errorMessage('Please choose one option in print, video and certificate');
//                 }
//            }

            if ($service == 'nikah_with_wali' && !array_key_exists('wali_email', $request_data)) {
                return errorMessage('Please Provide wali email in case your own wali selected');
            }

            if ($service == 'own_witness' && !array_key_exists('witness_email', $request_data)) {
                return errorMessage('Please Provide witness emails in case your witnesses selected');
            }
        }
    }

    public function resendInvitation($request)
    {
        $request_data = $request->all();
        $request_data['user_id'] = auth()->id();
        $user_type = $request->user_type;
        $nikah = Nikah::find($request->nikah_id);
        $password = generateStrongPassword();

        if ($user_type == 'wali') {
            $old_wali = $request_data['old_user'][0];
            $request_data['wali_email'] = $request_data['email'][0];
            if ($request_data['wali_email'] == $old_wali) {
                $wali_user = NikahRelatedService::saveUserAsWali($request_data, $nikah, $password);

//                dd($wali_user,$old_wali);
//                $check_walli = Walli::where('user_as_wali_id',$wali_user->id)->where('nikah_id',$nikah->id)->first();
//                if($check_walli && $check_walli->is_invitation_accepted == 0){
//                    return Api::error('This person '.$old_wali.' has already rejected invitation. Please invite new person');
//                }

            } else {
                $wali_user = NikahRelatedService::saveUserAsWali($request_data, $nikah, $password);
                $old_wali_user = User::where('email', $old_wali)->first();
                if($old_wali_user){
                    $delete_old_wali = Walli::where('user_as_wali_id', $old_wali_user->id)->forceDelete();
                    if ($old_wali) {
                        $check_partner_count = PartnerDetail::where('male_id', $old_wali_user->id)->orwhere('female_id', $old_wali_user->id)->count();
                        $check_wali_present_in_other_nikah = Walli::where('invited_by',$old_wali_user->id)->count();

                        if ($check_partner_count == 0 && $check_wali_present_in_other_nikah == 0) {
                            $old_wali_user->forceDelete();
                        }
                    }
                }

            }

            sendInviteToWalli($request_data['wali_email'], $nikah, $wali_user, $password);
            return Api::response($wali_user, 'Email sent successfully');
        }
        if ($user_type == 'witness') {

            foreach ($request['email'] as $key => $email) {
                $password = generateStrongPassword();
                $old_witness_email = $request_data['old_user'][$key];

                if ($email == $old_witness_email) {
                    $witness_user = User::where('email', $old_witness_email)->first();
                    $witness_user = NikahRelatedService::saveUserAsWitness($request_data, $email, $nikah, $password);

//                    $check_witness = Witness::where('user_as_witness_id',$witness_user->id)->where('nikah_id',$nikah->id)->first();
//                    if($check_witness && $check_witness->is_invitation_accepted == 0){
//                        return Api::error('This '.$request_data['old_user'][$key].' person has already rejected invitation. Please invite new person');
//                    }
                } else {
                    $witness_user = NikahRelatedService::saveUserAsWitness($request_data, $email, $nikah, $password);
                    $old_witness_user = User::where('email', $old_witness_email)->first();
                    if ($old_witness_user != null) {
                        $delete_old_witness = Witness::where('user_as_witness_id', $old_witness_user->id)->forceDelete();
                        $check_partner_count = PartnerDetail::where('male_id', $old_witness_user->id)->orwhere('female_id', $old_witness_user->id)->count();
                        $check_witness_present_in_other_nikah = Witness::where('invited_by',$old_witness_user->id)->count();

                        if ($check_partner_count == 0 && $check_witness_present_in_other_nikah == 0) {
                            $old_witness_user->forceDelete();
                        }
                    }
                }

                sendInviteToWitness($email, $nikah, $witness_user, $password);
            }
            return Api::response($witness_user, 'Email sent successfully');
        }
    }
}
