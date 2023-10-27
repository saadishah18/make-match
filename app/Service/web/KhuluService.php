<?php

namespace App\Service\web;

use App\Http\Resources\KhuluResource;
use App\Models\Khulu;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\Payments;
use App\Models\PortalSetting;
use App\Models\User;
use App\Service\Facades\Api;
use App\Service\ImamService;
use App\Service\NikahRelatedService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KhuluService
{
    public function khuluListing($request)
    {
        $khulus = Khulu::all();
        return KhuluResource::collection($khulus);
    }

    public function assignImamToKhulu($request)
    {
        $khulu = Khulu::where('nikah_id', $request->nikah_id)->first();
        if ($khulu) {
            $khulu->imam_id = $request->imam_id;
            $khulu->update();
            return successResponse($khulu, 'Imam Assigned successfully');
        } else {
            return errorMessage('Khulu Not found');
        }
    }

    public function khulaAssignedToImam($params = null, $imam_id)
    {
        $khulus = Khulu::where('imam_id', $imam_id)->get();
        return KhuluResource::collection($khulus);
    }

    public function updateKhuluPaymentStatusOLD($request)
    {
        return DB::transaction(function () use ($request) {
            $metadata = $request->all()['data']['object']['metadata'];
            $id = $request->all()['data']['object']['id'];
            $khulu = Khulu::find($metadata['khulu_id']);
            $post_data = $request->all();
            if ($khulu) {
                $khulu->request_data = json_encode($post_data);
                $khulu->payment_status = 'completed';
                $khulu->payment_id = $id;
                $khulu->update();

                $khula_price = formatNumbers(PortalSetting::where('name', 'khulu_fees')->first()->value);
                $total_price = includeVatInPrice($khula_price);

                Log::info('Khulu information saved');
                if ($khulu) {
                    $transactional_data = [
                        'activity_id' => $khulu->id,
                        'activity_name' => 'Khulu',
                        'male_id' => $khulu->male_id,
                        'female_id' => $khulu->partner_id,
                        'services_total_price' => $khula_price,
                        'total' => formatNumbers($total_price),
                        'transaction_id' => $post_data['id'],
                        'paid_by_platform' => 'stripe',
                        'status' => 'completed',
                    ];
                    $create_transaction = Payments::create($transactional_data);
                    Log::info('Khulu Payment information saved');
                }
                return response('success', 200);
            }
        });
    }

    public function updateKhuluPaymentStatus($request)
    {
        return DB::transaction(function () use ($request) {
            $metadata = $request['data']['object']['metadata'];
            $id = $request['data']['object']['id'];
//            $nikah = Khulu::find($metadata['nikah_id']);
            $post_data = $request;
            $femaleUser = User::find($metadata['female_user_id']);

            $nikah = Nikah::where('user_id',$metadata['female_user_id'])->orWhere('partner_id', $metadata['female_user_id'])->find($metadata['nikah_id']);
            if ($nikah) {
                $khulu = Khulu::where('nikah_id', $metadata['nikah_id'])->first();
                if ($khulu) {
//                    if ($khulu->{'2nd_khulu_applied_date'} == null) {
                    $khulu->{'2nd_khulu_applied_date'} = Carbon::now()->toDateTimeString();
                    $khulu->khulu_counter = 2;
                    $khulu->second_khulu_status = 'requested';
                    $khulu->second_khulu_reason = $metadata['reason'];
                    $khulu->second_khulu_detail = $metadata['detail'];
                    $khulu->imam_id = $khulu->imam_id != null || $khulu->imam_id != '' ? $khulu->imam_id : $nikah->imam_id;
                    $khulu->update();
//                    } else {
//                        return Api::error('You has already applied for 2nd khulu');
//                    }
                } else {
                    $khulu = Khulu::create([
                        'male_id' => $femaleUser->id == $nikah->user_id ? $nikah->partner_id : $nikah->user_id,
                        'partner_id' => $femaleUser->id,
                        'nikah_id' => $metadata['nikah_id'],
                        'khulu_counter' => 1,
                        'otp_verified' => 1,
                        'first_khulu_status' => 'requested',
                        '1st_khulu_applied_date' => Carbon::now()->toDateTimeString(),
                        'reason' => $metadata['reason'],
                        'details' => $metadata['detail'],
                        'imam_id' => $nikah->imam_id,
                    ]);
                }
                $khulu->payment_status = 'completed';
                $khulu->payment_id = $id;
                $khulu->update();

                $khula_price = formatNumbers(PortalSetting::where('name', 'khulu_fees')->first()->value);
                $total_price = includeVatInPrice($khula_price);
                $transactional_data = [
                    'activity_id' => $khulu->id,
                    'activity_name' => 'Khulu',
                    'male_id' => $khulu->male_id,
                    'female_id' => $khulu->partner_id,
                    'services_total_price' => $khula_price,
                    'total' => formatNumbers($total_price),
                    'transaction_id' => $post_data['id'],
                    'paid_by_platform' => 'stripe',
                    'status' => 'completed',
                ];
                $create_transaction = Payments::create($transactional_data);
                Log::info('Khulu Payment information saved');
                return response('success', 200);
            }


        });
    }

    public function validateKhulu($request){
        $khulu = Khulu::find($request->khulu_id);
        if($khulu->khulu_counter == 1){
            $khulu->first_khulu_status = 'completed';
        }else{
            $khulu->second_khulu_status = 'completed';
        }
        $khulu->is_validated = 1;
        $khulu->update();
        $nikahHistory = NikahDetailHistory::where('nikah_id',$khulu->nikah_id)->first();
        if($nikahHistory){
            $nikahHistory->is_khulu_applied = 1;
            $nikahHistory->khulu_id = $khulu->id;
            $nikahHistory->current_status = 'Divorced';
            $nikahHistory->update();
        }
        if($khulu){
            return true;
        }
    }

    public function rejectKhulu($request){
        $khulu = Khulu::find($request->khulu_id);
        if($khulu->khulu_counter == 1){
            $khulu->first_khulu_status = 'rejected';
        }else{
            $khulu->second_khulu_status = 'rejected';
        }
        $khulu->update();
        if($khulu){
            return true;
        }
    }
}
