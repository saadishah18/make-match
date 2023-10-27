<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => $this->password,
            'phone' => $this->phone,
            'country_code' => $this->country_code,
            'country_name' => $this->country_name,
            'phone_verified_at' => $this->phone_verified_at,
            'address' => $this->address,
            'gender' => $this->gender,
            'id_card_number' => $this->id_card_number,
            'id_expiry' => $this->id_expiry,
            'date_of_birth' => $this->date_of_birth,
            'qr_number' => $this->qr_number,
            'profile_image' => $this->profile_image ? imagePath($this->profile_image,'profile_image') : null,
            'id_card_front' => $this->id_card_front ? imagePath($this->id_card_front,'id_card_front') : null,
            'id_card_back' => $this->id_card_back ? imagePath($this->id_card_back,'id_card_front') : null,
            'selfie' => $this->selfie ? imagePath($this->selfie,'selfie') : null,
            'created_at' => $this->created_at,
        ];
    }
}
