<?php

namespace App\Repositories;

use App\Http\Resources\TalaqResource;
use App\Mail\TalaqMail;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\PregnancyDetail;
use App\Models\Talaq;
use App\Models\User;
use App\Repositories\Interfaces\TalaqInterface;
use App\Service\Facades\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TalaqRepository implements TalaqInterface
{
    public function applyTalaq($request)
    {
        $request->validate(['nikah_id' => 'required', 'exists:nikahs,id']);
        $user = Auth::user();
        $nikah = Nikah::where('id', $request->nikah_id)->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('partner_id', $user->id);
        })->first();
        if ($nikah) {
            $femalePartnerId = Auth::user()->id == $nikah->partner_id ? $nikah->user_id : $nikah->partner_id;
            $femalePartner = User::find($femalePartnerId);
            $talaq = Talaq::firstOrCreate(['nikah_id' => $nikah->id], [
                'male_id' => Auth::user()->id,
                'partner_id' => $femalePartner->id,
                'nikah_id' => $nikah->id,
                'is_confirmed_by_otp' => 1
            ]);
            //talaq certificate pending
            if ($talaq->is_ruju_applied == null && ($talaq->{'1st_talaq_date'} == null || empty($talaq->{'1st_talaq_date'}))) {
                $talaq->update([
                    '1st_talaq_date' => Carbon::now()->toDateTimeString(),
                    'talaq_counter' => 1,
                ]);

            } elseif ($talaq->talaq_counter == 1 && ($talaq->{'2nd_talaq_date'} == null || empty($talaq->{'2nd_talaq_date'}))) {
                $talaq->update([
                    '2nd_talaq_date' => Carbon::now()->toDateTimeString(),
                    'talaq_counter' => 2,
                ]);
            } elseif($talaq->talaq_counter == 2 && ($talaq->{'3rd_talaq_date'} == null || empty($talaq->{'3rd_talaq_date'}))) {
                $talaq->update([
                    '3rd_talaq_date' => Carbon::now()->toDateTimeString(),
                    'talaq_counter' => 3,
                ]);
            }
            if($talaq->talaq_counter == 3){
                $nikah_history = NikahDetailHistory::where('nikah_id',$request->nikah_id)->first();
                $nikah_history->is_talaq_applied = 1;
                $nikah_history->talaq_id = $talaq->id;
                $nikah_history->current_status = 'Divorced';
                $nikah_history->update();
            }
            Mail::to($femalePartner->email)->send(new TalaqMail(Auth::user(),$femalePartner, new TalaqResource($talaq)));
            $data = [];
            $data['talaq'] = new TalaqResource($talaq);
            return Api::response($data, 'Talaq applied Successfully');
        }
        return Api::response([], 'Nikah not found');
    }

    public function addPregnancyDetail($request){
        $talaq_detail = Talaq::find($request->talaq_id);
        $check_pregnancy_exists = PregnancyDetail::where('talaq_id',$request->talaq_id)->where('female_id',$request->female_id)->first();
        if($check_pregnancy_exists){
            $check_pregnancy_exists->expected_date = date('Y-m-d',strtotime($request->expected_date));
            $check_pregnancy_exists->update();
            return $check_pregnancy_exists;
        }else{
            $data = [
                'talaq_id'=> $request->talaq_id,
                'nikah_id'=> $talaq_detail->nikah_id,
                'male_id'=> $talaq_detail->male_id,
                'female_id'=> $request->female_id,
                'expected_date' => date('Y-m-d',strtotime($request->expected_date)),
            ];
            $create_record = PregnancyDetail::create($data);
            return $create_record;
        }
    }
}
