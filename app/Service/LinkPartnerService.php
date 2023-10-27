<?php

namespace App\Service;

use App\Models\PartnerDetail;

class LinkPartnerService
{
    public static function checkInvitationExists($email, $user){
        $user_invitation_exists = PartnerDetail::where('requested_to_be_partner_email',$email)
            ->where( 'requested_by', $user->id)->first();
        return $user_invitation_exists;
    }

    public static function checkPartnerAlreadyLinked($male_id, $female_id){
        $check_partner_already_bind_with_other_person = PartnerDetail::where('male_id', $male_id)->orwhere('female_id', $female_id)->first();
        return $check_partner_already_bind_with_other_person;
    }
}
