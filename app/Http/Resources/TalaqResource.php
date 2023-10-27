<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TalaqResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $talaq_date = null;
        if($this->{'1st_talaq_date'} != null){
            $talaq_date = $this->{'1st_talaq_date'};
        }
        if($this->{'2nd_talaq_date'} != null){
            $talaq_date = $this->{'2nd_talaq_date'};
        }
        if($this->{'3rd_talaq_date'} != null){
            $talaq_date = $this->{'3rd_talaq_date'};
        }
        return [
            'id'=> $this->id,
            'nikah_id' => $this->nikah_id,
            'first_talaq_date' => $this->{'1st_talaq_date'},
            'second_talaq_date' => $this->{'2nd_talaq_date'},
            'third_talaq_date' => $this->{'3rd_talaq_date'},
            'talaq_counter' => $this->talaq_counter,
            'is_ruju_applied' => $this->is_ruju_applied == 1 ? $this->is_ruju_applied : 0,
            'first_talaq_certificate_url' => null,
            'second_talaq_certificate_url' => null,
            'third_talaq_certificate_url' => null,
            'requester' => $this->nikah->history ? fullName($this->nikah->history->groom->first_name,$this->nikah->history->groom->last_name) : '',
            'bride' => $this->nikah->history ? fullName($this->nikah->history->bride->first_name,$this->nikah->history->bride->last_name) : '',
            'pregnancy_detail' => $this->pregnancyDetail ? Carbon::parse($this->pregnancyDetail->expected_date) : '',
            'type' => 'talaq',
            'talaq_date' => $talaq_date,
            'talaq_date_for_web' => $talaq_date != null ?  Carbon::parse($talaq_date)->toFormattedDateString() : null,
            'wali' => null,
            'assigned_imam' => $this->nikah->assignedImam ? fullName($this->nikah->assignedImam->first_name, $this->nikah->assignedImam->last_name) : ''
        ];
    }
}
