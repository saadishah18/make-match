<?php


namespace App\Repositories;

use App\Events\SendPartnerInvitationEmail;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\UserResource;
use App\Mail\LinkPartnerMail;
use App\Mail\LinkPartnerSuccessMail;
use App\Models\Khulu;
use App\Models\Nikah;
use App\Models\NikahType;
use App\Models\PartnerDetail;
use App\Models\PortalSetting;
use App\Models\ServiceObtained;
use App\Models\Services;
use App\Models\User;
use App\Repositories\Interfaces\Repository;
use App\Repositories\Interfaces\UserInterface;
use App\Service\Facades\Api;
use App\Service\LinkPartnerService;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Psy\Readline\Libedit;

class UserRepository implements UserInterface
{

    public function get_all(): \Illuminate\Http\JsonResponse
    {
        return Api::response();
    }

    public function get_one($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if (!$user) return Api::not_found();
        return Api::response(new UserResource($user));
    }

    public function add(): \Illuminate\Http\JsonResponse
    {
        return Api::response();
    }

    public function update($model)
    {
        $request = request();

        // todo @saad User is unique on the basis of Name & DOB
        if ($request->has('first_name') && $request->first_name != ""){
            $model->first_name = $request->first_name;
        }
        if ($request->has('last_name') && $request->last_name != ""){
            $model->last_name = $request->last_name;
        }

        // todo @saad verify phone is unique before updating
        if ($request->has('phone') && $request->phone != ""){
            $model->phone = $request->phone;
        }
        if ($request->has('country_name') &&  $request->country_name != ""){
            $model->country_name = $request->country_name;
        }
        if ($request->has('country_code') &&  $request->country_code != ""){
            $model->country_code = $request->country_code;
        }

        if ($request->has('country_code') &&  $request->phone_verified_at != ""){
            $model->phone_verified_at = $request->phone_verified_at;
        }


        if ($request->has('address') &&  $request->address != ""){
            $model->address = $request->address;
        }
        if ($request->has('gender') &&  $request->gender != ""){
            $model->gender = $request->gender;
        }
        if ($request->has('id_card_number') &&  $request->id_card_number != ""){
            $model->id_card_number = $request->id_card_number;
        }
        if ($request->has('id_expiry') &&  $request->id_expiry != ""){
            $model->id_expiry = date('Y-m-d',strtotime($request->id_expiry));
        }
        if ($request->has('date_of_birth') &&  $request->date_of_birth != ""){
            $model->date_of_birth = date('Y-m-d',strtotime($request->date_of_birth));
        }
//        if ($request->has('password')){
//            $model->password = Hash::make($request->password);
//        }

        if ($request->has('profile_image')) {

            $image_validation = $this->image_validation($request->file('profile_image'));

            if ($image_validation) {
                if ($model->profile_image && file_exists(public_path('storage/' . $model->profile_image))) {
                    unlink(public_path('storage/' . $model->profile_image));
                }
                $path = $request->file('profile_image')->store('/files/profile', 'public');
                $model->profile_image = $path;
            }
        }

        if ($request->has('selfie')) {

            $image_validation = $this->image_validation($request->file('selfie'));
            if ($image_validation) {
                if ($model->selfie && file_exists(public_path('storage/' . $model->selfie))) {
                    unlink(public_path('storage/' . $model->selfie));
                }
                $path = $request->file('selfie')->store('/files/selfie', 'public');
                $model->selfie = $path;
            }
        }

        if ($request->has('id_card_front')) {
            $image_validation = $this->image_validation($request->file('id_card_front'));
            if ($image_validation) {
                if ($model->id_card_front && file_exists(public_path('storage/' . $model->id_card_front))) {
                    unlink(public_path('storage/' . $model->id_card_front));
                }
                $path = $request->file('id_card_front')->store('/files/id_cards', 'public');
                $model->id_card_front = $path;
            }
        }

        if ($request->has('id_card_back')) {
            $image_validation = $this->image_validation($request->file('id_card_back'));
            if ($image_validation) {
                if ($model->id_card_back && file_exists(public_path('storage/' . $model->id_card_back))) {
                    unlink(public_path('storage/' . $model->id_card_back));
                }
                $path = $request->file('id_card_back')->store('/files/id_cards', 'public');
                $model->id_card_back = $path;
            }
        }
        $model->update();
        $code = '';
        if (isset($request->send_otp) && $request->send_otp == 1) {
            $code =  send_otp($model, $request->otp_type);
            $message = trans('auth.otp_sent', ['medium' => 'email', 'digit' => 4]);
        } else {
            $message = trans('auth.profile_updated');
        }
        $return_array = [
            'user' => $model,
            'code' => $code,
            'message' => $message
        ];
        return $return_array;
    }

    public function remove($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if (!$user) return Api::not_found();
        return Api::response([
            'removed' => $user->delete()
        ]);
    }

    public function image_validation($image)
    {
        if($image != null || $image  != ''){
            $fileExtension = substr(strrchr($image->getClientOriginalName(), '.'), 1);
            if ($fileExtension != 'jpg' && $fileExtension != 'jpeg' && $fileExtension != 'png' && $fileExtension != 'gif') {
                return Api::error('Image extension should be jpeg,jpg,png,and gif');
            }
            $filesize = \File::size($image);
            if ($filesize >= 1024 * 1024 * 20) {
                return Api::error('Image size should less than 20 mb');
            }

            return true;
        }
    }

    public function linkPartner($request)
    {
        if (!UserService::checkProfileCompletion(auth()->user())) {
            $return_array = [
                'error' => true,
                'partner_detail' => null,
                'message' => trans('response.complete_profile')
            ];
            return $return_array;
        }

        if (isset($request['email']) && $request['email'] != null) {
            $user = User::whereEmail($request['email'])->first();
        }
//        if (isset($request['phone'])) {
//            $user = User::wherePhone($request['phone'])->first();
//        }
        if (isset($request['qr_code']) && $request['qr_code'] != null) {
            $user = User::firstWhere('qr_number', $request['qr_code']);
            if(!$user){
                return errorMessage('Invalid Qr Number');
            }
        }

        if(!$user){
//            $check_invitation_exits = PartnerDetail::where('requested_to_be_partner_email',$request['email'])
//                ->where( 'requested_by', auth()->id())->first();

            if(LinkPartnerService::checkInvitationExists($request['email'],auth()->user())){

                //convert this to event queue listener
//                Mail::to($request['email'])->send(new LinkPartnerMail(auth()->user()));
                SendPartnerInvitationEmail::dispatch($request['email'],auth()->user());
//                event(new SendPartnerInvitationEmail($request['email'],auth()->user()));

            }else{
                SendPartnerInvitationEmail::dispatch($request['email'],auth()->user());
//                event(new SendPartnerInvitationEmail($request['email'],auth()->user()));

                $create_partner_data = [
                    'requested_by' => auth()->id(),
                    'requested_to_be_partner_email' => $request['email'],
                    'requested_started_by_person_qr' => auth()->user()->qr_number,
                ];

                $create_partner = PartnerDetail::create($create_partner_data);
            }
            $return_array = [
                'partner_detail' => null,
                'message' => 'Email sent to partner for approval'
            ];
            return $return_array;
        }else{
            if ($user->gender == null) {
                $return_array = [
                    'error' => true,
                    'partner_detail' => null,
                    'message' => 'Partner Profile not completed. Please Complete profile first'
                ];
                return $return_array;
            }
            if (checkGender(auth()->user()) == $user->gender) {
                $return_array = [
                    'error' => true,
                    'partner_detail' => null,
                    'message' => trans('response.gender_difference')
                ];
                return $return_array;
            }

            if (auth()->user()->gender == 'male' && $user->gender == 'female') {
                $male_id = auth()->id();
                $female_id = $user->id;
            }
            elseif (auth()->user()->gender == 'female' && $user->gender == 'male') {
                $female_id = auth()->id();
                $male_id = $user->id;
            }

           if (LinkPartnerService::checkPartnerAlreadyLinked($male_id, $female_id)) {
                $return_array = [
                    'error' => true,
                    'message' => trans('response.already_partner')
                ];
                return $return_array;
            } else {
//                $check_user_invitation_data = PartnerDetail::where('requested_to_be_partner_email',$user->email)
//                    ->where( 'requested_by', auth()->id())->first();



               $check_user_invitation_data = LinkPartnerService::checkInvitationExists($user->email, auth()->user());

                if($check_user_invitation_data){
                    $check_user_invitation_data->male_id = $male_id;
                    $check_user_invitation_data->female_id = $female_id;
                    $check_user_invitation_data->requested_by = $user->id;
                    $check_user_invitation_data->requested_to_be_partner = $user->id;
                    $check_user_invitation_data->update();
                    $return_array = [
                        'partner_detail' => new PartnerResource($check_user_invitation_data->refresh()),
                        'message' => trans('response.partner_created')
                    ];
                }else{
                    $create_partner_data = [
                        'male_id' => $male_id,
                        'female_id' => $female_id,
                        'requested_by' => auth()->id(),
                        'requested_to_be_partner_email' => $user->email,
                        'requested_to_be_partner' => $user->id,
                        'requested_started_by_person_qr' => auth()->user()->qr_number,
                    ];
                    $create_partner = PartnerDetail::create($create_partner_data);
                    $return_array = [
                        'partner_detail' => new PartnerResource($create_partner),
                        'message' => trans('response.partner_created')
                    ];
                }
//               SendPartnerInvitationEmail::dispatch($request['email'],auth()->user());
               Mail::to($user->email)->queue(new LinkPartnerSuccessMail(auth()->user()));
                return $return_array;
            }
        }
    }


    public function qrCode($user)
    {
        // TODO: Implement qrCode() method.
        if ($user->qr_number == null) {
            $qr_number = User::generateQRNumber();
            $user->qr_number = $qr_number;
            $user->update();
//            $qr_image = asset('files/qrcodes/').'/'. $qr_number . '.png';
            $return_array = [
                'qr_number' => $qr_number,
//                'qr_image' => $qr_image,
            ];
            return $return_array;

        } else {
//            $qr_image = asset('files/qrcodes').'/'. $user->qr_number . '.png';
            $return_array = [
                'qr_number' => $user->qr_number,
//                'qr_image' => $qr_image,
            ];
            return $return_array;
        }
    }

    public function userTransactions()
    {
        $user = auth()->user();
        if($user->gender == 'male'){
            $transactions = $user->malePaymentTransaction;

        } elseif($user->gender == 'female'){
            $transactions = $user->femalePaymentTransaction;
        }
        $result = [];
        foreach ($transactions as $key => $transaction){
            $khulu_price_without_vat = 0;
            $khulu_price_with_vat = 0;
            $total_price = 0;
            $vat_price = 0;
            $services = [];
            if($transaction->activity_name == 'Nikah'){
                $nikah = Nikah::find($transaction->activity_id);
                if($nikah){
                    $services_obtained_ids = ServiceObtained::where('nikah_id',$nikah->id)->get()->pluck('service_id')->toArray();
                    $services = Services::select('name','price')->whereIN('id',$services_obtained_ids)->get()->toArray();
                    $imam_service = Services::where('slug','nikah_imam')->first();
                    if($imam_service){

                        $imam_fees = [
                            'name' => $imam_service->name,
                            'price' => formatNumbers($imam_service->price),
                        ];
                        array_push($services,$imam_fees);

                    }

                    $total_price = $transaction->services_total_price ;
                    $vat_price = $transaction->total;
                }

            }else{
                $khulu = Khulu::find($transaction->activity_id);
                $khulu_price_without_vat = $transaction->services_total_price ;
                $khulu_price_with_vat = $transaction->total;
            }
            $result[$key] = [
                'nikah_type' => $nikah ?  $nikah->type->name : '',
                'activity_type' => isset($khulu) ? 'Khulu' : $nikah->type->name,
                'nikah_price' => !isset($khulu) ? formatNumbers($nikah->type->price) : null,
                'nikah_date' => !isset($khulu) ? Carbon::parse($nikah->nikah_date)->toDateTimeLocalString(): null,
                'khula_date' => isset($khulu) ? Carbon::parse($khulu->created_at)->toDateTimeLocalString(): null,
                'total_price_without_vat' => $nikah ? formatNumbers($total_price) : null,
                'total_price' => $nikah ? formatNumbers($vat_price) : null,
                'services' => $services,
                'khulu_price' => isset($khulu) ? $khulu_price_without_vat : null,
                'khulu_price_with_vat' => isset($khulu) ? $khulu_price_with_vat : null,
                'vat_included' =>  formatNumbers(PortalSetting::where('name','vat')->first()->value),
            ];
        }
        return $result;
    }
}
