<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaliResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = 2;
        if($this->is_invitation_accepted == 0){
            $status = 0;
        }elseif ($this->is_invitation_accepted == 1){
            $status = 1;
        }
        return [
            'full_name' => $this->user->first_name != null &&$this->user->last_name != null ? fullName($this->user->first_name,$this->user->last_name) : null,
            'email' => $this->user ? $this->user->email : '',
            'status' => $status,
        ];
    }
}
