<?php

namespace App\Http\Resources;

use App\Models\Payments;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $is_accept = 0;
        $user_type = null;
        if($this->currentUserAsWali != null){
            $is_accept = $this->currentUserAsWali->is_invitation_accepted;
            $user_type = 'wali';
        }
        if($this->currentUserAsWitness != null){
            $is_accept = $this->currentUserAsWitness->is_invitation_accepted;
            $user_type = 'witness';
        }
        $nikah_date_time = $this->nikah_date.' '.$this->start_time;
        return [
            'user'=> $this->user ? $this->user->only('id','first_name','last_name','email','gender'):null ,
            'partner'=> $this->partner ? $this->partner->only('id','first_name','last_name','email','gender'):null ,
            'nikah_date' => Carbon::parse($this->nikah_date),
            'nikah_date_time' => Carbon::parse($nikah_date_time),
            'invited_by'=> $this->user ? $this->user->email:null,
            'link'=> $this->zoom_join_url,
            'current_user_as_wali' => $this->currentUserAsWali ?  new WaliResource($this->currentUserAsWali):null,
            'current_user_as_witness' => $this->currentUserAsWitness ?  new WitnessResource($this->currentUserAsWitness) :null,
            'is_accept' => $is_accept,
            'user_type' => $user_type,
            'nikah_id' => $this->id,
        ];
    }
}
