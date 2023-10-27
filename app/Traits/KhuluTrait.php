<?php
namespace App\Traits;

use App\Models\Khulu;

trait KhuluTrait{

    public function canApplyKhulu($user){
        $can_apply_khulu = Khulu::where('male_id',$user->id)->orWhere('partner_id',$user->id)->exists();
        return $can_apply_khulu;
    }

    public function getKhuluDetail($user){
        $khula_detial = Khulu::where('male_id',$user->id)->orWhere('partner_id',$user->id)->first();
        return $khula_detial;
    }
}
