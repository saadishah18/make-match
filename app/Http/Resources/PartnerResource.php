<?php

namespace App\Http\Resources;

use App\Models\Nikah;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this);
        if(checkGender(auth()->user()) == 'male'){
//            $user_detail = User::where('id',$this);
            if(auth()->id() == $this->requested_by){
                return [
                    'partner_id' => $this->userAsPartnerData->id,
                    'partner_name' => $this->userAsPartnerData->first_name . ' ' . $this->userAsPartnerData->last_name,
                    'partner_email' => $this->userAsPartnerData->email,
                    'partner_phone' => $this->userAsPartnerData->phone,
                    'partner_image' => imagePath($this->userAsPartnerData->profile_image, 'profile_image'),
                    'partner_qr' => $this->userAsPartnerData->qr_number,
                    'partner_dob' => $this->userAsPartnerData->date_of_birth,
                    'nikah_detail' => Nikah::with('talaqs.pregnancyDetail:talaq_id,expected_date')
                        ->where('user_id',$this->userAsPartnerData->id)
                        ->orWhere('partner_id',$this->userAsPartnerData->id)->get(),
                ];
            }elseif(auth()->id() == $this->requested_to_be_partner){
                return [
                    'partner_id' => $this->requestedByPersonData->id,
                    'partner_name' => $this->requestedByPersonData->first_name . ' ' . $this->requestedByPersonData->last_name,
                    'partner_email' => $this->requestedByPersonData->email,
                    'partner_phone' => $this->requestedByPersonData->phone,
                    'partner_image' => imagePath($this->requestedByPersonData->profile_image, 'profile_image'),
                    'partner_qr' => $this->requestedByPersonData->qr_number,
                    'partner_dob' => $this->requestedByPersonData->date_of_birth,
                    'nikah_detail' => Nikah::with('talaqs.pregnancyDetail:talaq_id,expected_date')->where('user_id',$this->requestedByPersonData->id)->orWhere('partner_id',$this->requestedByPersonData->id)->get(),
                ];
            }
        }else{
            return [
//            'id' => $this->id,
                'partner_id' => $this->malePartnerUser->id,
                'partner_name' => $this->malePartnerUser->first_name . ' ' . $this->malePartnerUser->last_name,
                'partner_email' => $this->malePartnerUser->email,
                'partner_phone' => $this->malePartnerUser->phone,
                'partner_image' => imagePath($this->malePartnerUser->profile_image, 'profile_image'),
                'partner_qr' => $this->malePartnerUser->qr_number,
                'partner_dob' => $this->malePartnerUser->date_of_birth,
                'nikah_detail' => Nikah::with('talaqs.pregnancyDetail:talaq_id,expected_date')
                    ->where('user_id',$this->malePartnerUser->id)
                    ->orWhere('partner_id',$this->malePartnerUser->id)->get(),
            ];
        }
    }
}
