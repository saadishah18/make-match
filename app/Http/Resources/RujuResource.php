<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RujuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $first_status = $this->first_ruju_status;
        $second_status =$this->second_ruju_status;

        if($first_status  === 'complete'){
            $first_status = 'completed';
        }
        if($second_status  === 'complete'){
            $second_status = 'completed';
        }
        return [
            'id' => $this->id,
            'male_id' => $this->male_id,
            'partner_id' => $this->partner_id,
            'talaq_id' => $this->talaq_id,
            'nikah_id' => $this->nikah_id,
            '1st_ruju_applied_date' => $this->{'1st_ruju_applied_date'},
            'first_ruju_applied_date' => $this->{'1st_ruju_applied_date'},
            '2nd_ruju_applied_date' => $this->{'2nd_ruju_applied_date'},
            'ruju_date' => $this->{'2nd_ruju_applied_date'} != null ? Carbon::parse($this->{'2nd_ruju_applied_date'})->toFormattedDayDateString() : Carbon::parse($this->{'1st_ruju_applied_date'})->toFormattedDayDateString(),

            'first_ruju_status' => $first_status,
            'second_ruju_status' => $second_status,
            'ruju_counter' => $this->ruju_counter,
            'groom' => fullName($this->nikah->history->groom->first_name,$this->nikah->history->groom->last_name),
            'requester' => fullName($this->nikah->history->bride->first_name,$this->nikah->history->bride->last_name),
        ];
    }
}
