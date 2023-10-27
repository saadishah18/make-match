<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KhuluResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        dd($this->imam_id == null && $this->nikah->assignedImam);
        return [
            'id' => $this ? $this->id : null,
            'male_id' => $this->male_id,
            'partner_id' => $this->partner_id,
            'nikah_id' => $this->nikah_id,
//            '1st_khulu_applied_date' => $this->{'1st_khulu_applied_date'},
            'first_khulu_applied_date' => $this->{'1st_khulu_applied_date'},
            '2nd_khulu_applied_date' => $this->{'2nd_khulu_applied_date'},
            'first_khulu_status' => $this->first_khulu_status,
            'second_khulu_status' => $this->second_khulu_status,
            'reason' => $this->reason,
            'detail' => $this->details,
            'second_khulu_reason' => $this->second_khulu_reason,
            'second_khulu_detail' => $this->second_khulu_detail,
            'khulu_counter' => $this->khulu_counter,
            'groom' => $this->nikah->history ? fullName($this->nikah->history->groom->first_name,$this->nikah->history->groom->last_name) : '',
            'groom_email' => $this->nikah->history ? $this->nikah->history->groom->email : '',
            'requester' => $this->nikah->history ? fullName($this->nikah->history->bride->first_name,$this->nikah->history->bride->last_name) : '',
            'requester_email' => $this->nikah->history ? $this->nikah->history->bride->email : '',
            'assigned_imam' => $this->imam_id == null ?   fullName($this->khuluImam->first_name,$this->khuluImam->last_name) : fullName($this->nikah->assignedImam->first_name, $this->nikah->assignedImam->last_name),
            'assigned_imam_id' => $this->imam_id == null  ? $this->nikah->assignedImam->id : null,
            'imam_is_active' => $this->imam_id == null ? $this->khuluImam->is_active : $this->nikah->assignedImam->is_active ,
            'khulu_assigned_imam_id' => $this->imam_id,
            'khulu_assigned_imam' => $this->imam_id == null ? fullName($this->khuluImam->first_name,$this->khuluImam->last_name) : '',
            'khula_date' => $this->{'2nd_khulu_applied_date'} != null ? Carbon::parse($this->{'1st_khulu_applied_date'})->toFormattedDayDateString() : Carbon::parse($this->{'1st_khulu_applied_date'})->toFormattedDayDateString(),
            'checkout_url' => $this->checkout_url != '' ? $this->checkout_url : '',
            'type' => 'khulu',
            'is_validated' => $this->is_validated
        ];
    }
}
