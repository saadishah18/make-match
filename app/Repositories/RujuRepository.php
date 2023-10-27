<?php

namespace App\Repositories;

use App\Http\Resources\RujuResource;
use App\Models\Ruju;
use App\Models\Talaq;
use App\Repositories\Interfaces\RujuInterface;
use App\Service\Facades\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RujuRepository implements RujuInterface
{
    public function applyRuju($request)
    {
        $talaq = Talaq::find($request->talaq_id);
        $ruju = Ruju::where('talaq_id', $talaq->id)->first();
        $gender = Str::lower(Auth::user()->gender);
        if($gender == 'male'){
            $status = 'complete';
        }else{
            $status = 'requested';
        }
        if ($ruju) {
            if($ruju->first_ruju_status == 'rejected' && $ruju->ruju_counter == 1 &&  $talaq->talaq_counter == 1){
                $ruju->first_ruju_status = $status;
                $ruju->update();

            }elseif ($ruju->second_ruju_status == 'rejected' && $ruju->ruju_counter == 2 && $talaq->talaq_counter == 2){
                $ruju->second_ruju_status = $status;
                $ruju->update();
            }elseif ($this->canApplySecondRuju($ruju, $talaq)) {
                $ruju->{'2nd_ruju_applied_date'} = Carbon::now()->toDateString();
                $ruju->ruju_counter++;
                $ruju->second_ruju_status = $status;
                $ruju->save();
            } else {
                return Api::response([], 'You can not apply for Ruju');
            }
        } else {
            if($talaq->talaq_counter  == 1){
                $data_to_save = [
                    'male_id' => $talaq->male_id,
                    'partner_id' => $talaq->partner_id,
                    'nikah_id' => $talaq->nikah_id,
                    'talaq_id' => $talaq->id,
                    'ruju_counter' => 1,
                    'otp_verified' => 1,
                    'first_ruju_status' => $status,
                    '1st_ruju_applied_date' => Carbon::now()->toDateString(),
                ];
            }else if($talaq->talaq_counter  == 2){
                $data_to_save = [
                    'male_id' => $talaq->male_id,
                    'partner_id' => $talaq->partner_id,
                    'nikah_id' => $talaq->nikah_id,
                    'talaq_id' => $talaq->id,
                    'ruju_counter' => 1,
                    'otp_verified' => 1,
                    'second_ruju_status' => $status,
                    '2nd_ruju_applied_date' => Carbon::now()->toDateString(),
                ];
            }
            $ruju = Ruju::create($data_to_save);
            $talaq->update(['is_ruju_applied' => 1]);
        }

        $data = [];
        $data['ruju'] = new RujuResource($ruju);
        $talaq = Talaq::find($request->talaq_id);
        if($gender == 'male' && $status == 'complete'){
            $talaq->update([
                '1st_talaq_date' => null,
                '2nd_talaq_date' => null,
                '3rd_talaq_date' => null,
            ]);
        }
        return Api::response($data, 'Ruju applied successfully');
    }

    public function canApplySecondRuju($ruju, $talaq)
    {
        return $ruju->ruju_counter < 2 && $talaq->{'2nd_talaq_date'};
    }

    public function acceptRujuRequest($request)
    {
        $ruju = Ruju::find($request->ruju_id);
        $talaq = Talaq::find($ruju->talaq_id);
        if ($ruju->male_id == Auth::user()->id) {
            if (($ruju->first_ruju_status == 'requested' || $ruju->first_ruju_status == 'rejected') && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 1) {
                $ruju->update(['first_ruju_status' => 'complete']);
                $talaq->update([
                    '1st_talaq_date' => null,
                    '2nd_talaq_date' => null,
                    '3rd_talaq_date' => null,
                ]);
            } elseif (($ruju->second_ruju_status == 'requested' || $ruju->second_ruju_status == 'rejected') && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 2) {
                $ruju->update(['second_ruju_status' => 'complete']);
                $talaq->update([
                    '1st_talaq_date' => null,
                    '2nd_talaq_date' => null,
//                    '3rd_talaq_date' => null,
                ]);

            } elseif (($ruju->second_ruju_status == 'requested' || $ruju->second_ruju_status == 'rejected') && $ruju->ruju_counter == 2 && $talaq->talaq_counter == 2) {
                $ruju->update(['second_ruju_status' => 'complete']);
                $talaq->update([
                    '1st_talaq_date' => null,
                    '2nd_talaq_date' => null,
                    '3rd_talaq_date' => null,
                ]);

            } else {
                return Api::response([], "Ruju could\'t be accepted It is already complete or 3rd talaq has been applied");
            }
            $data = [];
            $data['ruju'] = new RujuResource($ruju);
            return Api::response($data, 'Ruju Accepted');
        }
        return Api::error('Unauthorized you can not accept this ruju');
    }

    public function rejectRujuRequest($request)
    {
        $ruju = Ruju::find($request->ruju_id);
        $talaq = Talaq::find($ruju->talaq_id);
        if ($ruju->male_id == Auth::user()->id) {
            if ($ruju->first_ruju_status == 'requested' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 1) {
                $ruju->update(['first_ruju_status' => 'rejected']);
            } elseif ($ruju->second_ruju_status == 'requested' && $ruju->ruju_counter == 2 && $talaq->talaq_counter == 2) {
                $ruju->update(['second_ruju_status' => 'rejected']);
            } else {
                return Api::response([], "Ruju could\'t be rejected it is already complete or 3rd talaq has been applied");
            }

            $data = [];
            $data['ruju'] = new RujuResource($ruju);
            return Api::response($data, 'Ruju Rejected Successfully');
        }
        return Api::error('Unauthorized you can not reject this ruju');
    }
}
