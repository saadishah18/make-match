<?php
namespace App\Traits;

use App\Models\Talaq;

trait TalaqTrait{

    public function checkUserTalaq($user){
        $check_talaq_exits = Talaq::where('male_id',$user->id)->orWhere('partner_id',$user->id)->exists();
        return $check_talaq_exits;
    }

    public function getUserTalaqDetail($user){
        $talaq_detail = Talaq::where('male_id',$user->id)->orWhere('partner_id',$user->id)->first();
        return $talaq_detail;
    }
}
