<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\KhuluResource;
use App\Http\Resources\TalaqResource;
use App\Http\Resources\UserResource;
use App\Models\PartnerDetail;
use App\Models\SocialUser;
use App\Models\User;
use App\Models\Walli;
use App\Models\Witness;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\UserRepository;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public $interface;

    public function __construct(UserInterface $interface)
    {
        $this->interface = $interface;
    }

    public function get_info(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = auth()->user();
            return Api::response(new UserResource($user));
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if (!Api::validate([
                'profile_image' => 'nullable|image|mimes:in:jpeg,png,jpg,gif,svg|max:10240',
                'selfie' => 'nullable|image|mimes:in:jpeg,png,jpg,gif,svg|max:10240',
                'phone' => 'nullable|' . Rule::unique('users')->ignore(auth()->id()),
                'email' => 'nullable|email|' . Rule::unique('users')->ignore(auth()->id()),
                'id_card_number' => 'nullable|' . Rule::unique('users')->ignore(auth()->id()),
                'id_card_front' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'id_card_back' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'gender' => 'nullable|string|in:male,female'
            ])) {
                return Api::validation_errors();
            }
            $user = auth()->user();
            $response = $this->interface->update($user);
            return Api::response(['user' => new UserResource($response['user']), 'code' => $response['code'], 'message' => $response['message']]);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function linkPartner(Request $request)
    {
        try {
            if (!Api::validate([
                'email' => 'required_without_all:qr_code,phone',
//                'phone' => 'required_without_all:email,qr_code',
                'qr_code' => 'required_without_all:email,phone'
            ])) {
                return Api::validation_errors();
            }

            /*if(($request['email'] === "" || $request["email"] === null) &&
                ($request['qr_code'] === "" || $request['qr_code'] === null)){
                return Api::error('Email OR Qr Code is required');
            }*/


            $response = $this->interface->linkPartner($request->all());

            if (isset($response['error']) && $response['error']) {
                return Api::error($response['message']);
            }
            return Api::response($response);
        } catch (\Exception $exception) {
            dd($exception->getFile(),$exception->getLine(),$exception->getMessage());
            return Api::server_error($exception);
        }
    }

    public function userQrCode()
    {
        try {
            $user = auth()->user();
            $response = $this->interface->qrCode($user);
            return Api::response($response);
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function getTransactionDetail(Request $request)
    {
        try {
            $response = $this->interface->userTransactions();
            return Api::response(['transactions' => $response]);
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function certificates()
    {
        try {
            $user = auth()->user();
            if ($user->gender == 'male') {
                $certificates = $user->maleCertificates;
            } else {
                $certificates = $user->femaleCertificates;
            }
            $result_array = [];
            foreach ($certificates as $certificate) {
                if ($certificate->activity_model == 'App/Nikah') {
                    $talaq_data = $certificate->nikah->talaqs != null ?  new TalaqResource($certificate->nikah->talaqs) : null;
                    $khulu_data = $certificate->nikah->khulu != null ?  new KhuluResource($certificate->nikah->khulu) : null;
                    $talaq_html = '';
                    if($talaq_data != null){
                        if($talaq_data->talaq_counter == 3 ){
                            $talaq_html = view('talaq-certificate',['talaq_data' => collect($talaq_data)])->render();
                        }
                    }

                    if($khulu_data != null){
                        $khulu_html = view('khulu-certificate',['talaq_data' => collect($khulu_data)])->render();
                    }

                    $result_array['Nikah'][] = [
                        'nikah_id' => $certificate->activity_id,
                        'nikah_type' => $certificate->nikah->type->name,
                        'id' => $certificate->id,
                        'system_certificate' => url($certificate->system_certificate),
                        'govt_certificate' =>  $certificate->govt_certificate != null ? asset('uploads/certificates/' . $certificate->govt_certificate) : null,
                        'talaq' => $talaq_data != null ? preg_replace('/\s+/', ' ',$talaq_html) : '',
                        'khulu' => $khulu_data != null && $khulu_data->is_validated == 1 ? preg_replace('/\s+/', ' ',$khulu_html) : '',
                    ];
                }
            }
            return Api::response($result_array, 'Certificate Listed');
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }


    public function deleteAccount(){
        try {
            $user = auth()->user();
            $partner =PartnerDetail::where('male_id',$user->id)->orWhere('female_id',$user->id)->first();
            $wali = Walli::where('user_as_wali_id',$user->id)->first();
            $witness = Witness::where('user_as_witness_id',$user->id)->first();
            if($partner){
                return Api::error('You are linked with a partner. Can not delete account');
            }elseif($wali){
                return Api::error('You are invited as walli in a nikah. Cannot delete your account');
            }
            elseif($witness){
                return Api::error('You are invited as Witness in a nikah. Cannot delete your account');
            }
            else{
                $social_user = SocialUser::where('user_id',$user->id)->delete();
                $user->forceDelete();
                return Api::response(null,'User deleted successfully');
            }
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

}
