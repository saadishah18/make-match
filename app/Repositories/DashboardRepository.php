<?php

namespace App\Repositories;

use App\Http\Resources\PartnerResource;
use App\Models\Nikah;
use App\Models\PartnerDetail;
use App\Models\PortalSetting;
use App\Models\PregnancyDetail;
use App\Models\Ruju;
use App\Models\Talaq;
use App\Repositories\Interfaces\DashboardInterface;
use App\Traits\KhuluTrait;
use App\Traits\NikahTrait;
use App\Traits\RujuTrait;
use App\Traits\TalaqTrait;
use Carbon\Carbon;

class DashboardRepository implements DashboardInterface
{
    use RujuTrait, NikahTrait, TalaqTrait, KhuluTrait;

    public function dashboardData()
    {
        // TODO: Implement dashboardData() method.
        $login_user = auth()->user();
        // check Nikah status

        $return_array['can_apply_nikah'] = 0;
        $return_array['can_apply_talaq'] = 0;
        $return_array['can_apply_ruju'] = 0;
        $return_array['can_apply_khulu'] = 0;
        $khulu_statuses1 = ['requested','requested'];
        $khulu_statuses2 = ['discarded','requested'];
        $khulu_statuses3 = ['discarded','rejected'];
        $khulu_statuses4 = ['rejected','discarded'];
        $khulu_statuses5 = ['rejected','rejected'];
        $khulu_statuses6 = ['discarded','discarded'];
        $khulu_statuses7 = ['requested','completed'];
        $khulu_statuses8 = ['requested', null];
        $khulu_statuses9 = ['rejected', 'requested'];
        $khulu_statuses10 = ['requested', 'discarded'];
        $return_array['nikah_status'] = '';
        $return_array['khulu_fees'] = formatNumbers(PortalSetting::where('name','khulu_fees')->count() ? PortalSetting::where('name','khulu_fees')->first()->value : 0);

        $nikah_exits = $this->checkUserNikahExits($login_user);

        $check_nikah_current_status = $this->checkNikahStatus($login_user);

        if (strtolower($login_user->gender) == 'male') {
            if ($login_user->femalePartners) {
                $return_array['partner_detail'] = PartnerResource::collection($login_user->femalePartners);
            } elseif (count($login_user->femalePartners) > 1) {
                $partner_array = [];
                foreach ($login_user->femalePartners as $partner_loop) {
                    $partner_array[] = new PartnerResource($partner_loop);
                    $return_array['partner_detail'] = $partner_array;
                }
            } else {
                $return_array['partner_detail'] = [];
            }
        } else {
            if ($login_user->malePartner != null) {
                $return_array['partner_detail'] = new PartnerResource($login_user->malePartner);
            } else {
                $return_array['partner_detail'] = [];
            }
        }

        if (checkGender($login_user) == 'male' && count($login_user->femalePartners) > 0 && !$nikah_exits) {
            $return_array['can_apply_nikah'] = 1;
        }

        if (checkGender($login_user) == 'female' && $login_user->malePartner != null > 0 && !$nikah_exits) {
            $return_array['can_apply_nikah'] = 1;
        }

        if($check_nikah_current_status) {

            // check talaq status
            $check_talaq_exits = $this->checkUserTalaq($login_user);
            $check_talaq_counter = $this->getUserTalaqDetail($login_user);

            // check Khulu status
            $khulu_detail = $this->getKhuluDetail($login_user);

            $talaq_counter = $check_talaq_exits ? $check_talaq_counter->talaq_counter : 0;
            if ((checkGender($login_user) == 'male' && $check_nikah_current_status->current_status == 'Nikahfied') && $talaq_counter < 3) {
                $return_array['can_apply_talaq'] = 1;
            }

            if ($check_nikah_current_status->current_status == 'Nikahfied' && $khulu_detail != null && ($khulu_detail->first_khulu_status != 'completed' || $khulu_detail->second_khulu_status != 'completed')) {
                $return_array['can_apply_talaq'] = 1;
            }

            // check Ruju status
//        $check_ruju_exits = $this->checkRujuExits($login_user);
            $check_can_apply_ruju = $this->canApplyRuju($login_user, $check_talaq_counter);

            if (($check_talaq_exits && $talaq_counter < 3) && $check_can_apply_ruju && $check_nikah_current_status->current_status == 'Nikahfied') {
                $return_array['can_apply_ruju'] = 1;
            }
            if (($login_user->gender == 'female' && $check_nikah_current_status->current_status == 'Nikahfied') && ($check_talaq_exits == false || $talaq_counter < 3)) {
                $return_array['can_apply_khulu'] = 1;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses1) && in_array($khulu_detail->second_khulu_status , $khulu_statuses1))){
                $return_array['can_apply_khulu'] = 0;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses2) && in_array($khulu_detail->second_khulu_status , $khulu_statuses2))){
                $return_array['can_apply_khulu'] = 0;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses3) && in_array($khulu_detail->second_khulu_status , $khulu_statuses3))){
                $return_array['can_apply_khulu'] = 1;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses4) && in_array($khulu_detail->second_khulu_status , $khulu_statuses4))){
                $return_array['can_apply_khulu'] = 1;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses9) && in_array($khulu_detail->second_khulu_status, $khulu_statuses9))){
                $return_array['can_apply_khulu'] = 0;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses5) && in_array($khulu_detail->second_khulu_status , $khulu_statuses5))){
                $return_array['can_apply_khulu'] = 1;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses6) && in_array($khulu_detail->second_khulu_status , $khulu_statuses6))){
                $return_array['can_apply_khulu'] = 1;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses7) && in_array($khulu_detail->second_khulu_status , $khulu_statuses7))){
                $return_array['can_apply_khulu'] = 0;
            }
            if($khulu_detail != null && (in_array($khulu_detail->first_khulu_status, $khulu_statuses8) && in_array($khulu_detail->second_khulu_status , $khulu_statuses8))){
                $return_array['can_apply_khulu'] = 0;
            }

            if ($khulu_detail != null && ($khulu_detail->first_khulu_status == 'completed' || $khulu_detail->second_khulu_status == 'completed')) {
                $return_array['can_apply_khulu'] = 0;
                $return_array['can_apply_ruju'] = 0;
                $return_array['can_apply_talaq'] = 0;
            }

            if ($check_nikah_current_status) {
                $return_array['nikah_status'] = $check_nikah_current_status->current_status == null ? 'Ready for Nikah' : $check_nikah_current_status->current_status;
            }
            if($this->isIddatCompleted($check_nikah_current_status->nikah_id, $check_nikah_current_status->male_id, $check_nikah_current_status->female_id)){
                $return_array['nikah_status'] = $check_nikah_current_status->current_status;
                $return_array['idat_check'] = 1;
            }else{
                $return_array['nikah_status'] = 'Idda Pending';
                $return_array['idat_check'] = 0;
            }

            if($khulu_detail != null && $khulu_detail->payment_status != 'completed'){
                $return_array['can_apply_khulu'] = 1;
            }

        }

        return $return_array;
    }

    public function isIddatCompleted($nikah_id,$male_id,$partner_id){

        $check_idat_passed = false;
        $talaq = Talaq::where('nikah_id',$nikah_id)->where('male_id',$male_id)->where('partner_id',$partner_id)->first();

        $pregnancy_reported = PregnancyDetail::where('nikah_id',$nikah_id)->where('male_id',$male_id)->where('female_id',$partner_id)->first();
        if($talaq){
            $ruju_status = Ruju::where('male_id',$male_id)->where('partner_id',$partner_id)->where('talaq_id',$talaq->id)->first();
            if($talaq->talaq_counter == 1 && $ruju_status && $ruju_status->ruju_counter == 1 && $ruju_status->first_ruju_status === 'complete'){
                return $check_idat_passed = true;
            }
            else if($talaq->talaq_counter == 2 && $ruju_status && $ruju_status->ruju_counter == 2 && $ruju_status->second_ruju_status === 'complete'){
                return $check_idat_passed = true;
            }
        }

        if ($pregnancy_reported) {
            $expected_date = $pregnancy_reported->expected_date;
            $days_from_expected_date = Carbon::parse($expected_date)->diffInDays(now());
            // Add the days from the expected_date to the talaq_date and check if 90 days have passed
            $check_idat_passed = Carbon::parse($expected_date)->addDays($days_from_expected_date)->isPast();
            return $check_idat_passed;
        }
        $talaq_date = null;
        if($talaq != null && $talaq->{'1st_talaq_date'} != null){
            $talaq_date = $talaq->{'1st_talaq_date'};
        }
        if($talaq != null && $talaq->{'2nd_talaq_date'} != null){
            $talaq_date = $talaq->{'2nd_talaq_date'};
        }
        if($talaq != null && $talaq->{'3rd_talaq_date'} != null){
            $talaq_date = $talaq->{'3rd_talaq_date'};
        }
        if($talaq_date != null){
            $check_idat_passed = Carbon::parse($talaq_date)->addDays(90)->isPast();
//        dd(Carbon::parse($talaq_date)->toFormattedDateString(),Carbon::parse($talaq_date)->addDays(90),$check_idat_passed);
            return $check_idat_passed;
        }else{
            return true;
        }

    }
}
