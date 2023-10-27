<?php

namespace App\Http\Resources;

use App\Models\Certificate;
use App\Models\Payments;
use App\Models\Services;
use App\Models\Walli;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NikahResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $service_obtained = $this->services->pluck('service_id')->toArray();
        $services = Services::whereIN('id', $service_obtained)->get();
        $payment = Payments::where('activity_id', $this->id)->first();
        $certifcate = Certificate::where('activity_id',$this->id)->where('activity_model','App/Nikah')->first();
        $nikah_start_time = Carbon::parse($this->nikah_date.''.$this->start_time);
        $nikah_end_time = Carbon::parse($this->nikah_date.''.$this->end_time);
       return [
            'nikah_id' => $this->id,
            'nikah_type' => $this->type->name,
            'nikah_type_price' => formatNumbers($this->type->price),
            'nikah_date' => Carbon::parse($this->nikah_date)->toDateTimeLocalString(),
            'n_date' => Carbon::parse($this->nikah_date),
            'services' => NikahServiceResource::collection($services),
            'total_price' => $payment ? formatNumbers($payment->total): 0.0,
            'payment_status' => $payment ? ucfirst($payment->status) : '',
            'wali' => in_array('nikah_with_wali', $services->pluck('slug')->toArray()) ? new WaliResource($this->wali): null,
            'witness' => in_array('own_witness', $services->pluck('slug')->toArray()) ? WitnessResource::collection($this->witnesses): null,
            'groom' => $this->history ? fullName($this->history->groom->first_name,$this->history->groom->last_name) : '',
            'bride' => $this->history ? fullName($this->history->bride->first_name,$this->history->bride->last_name) : '',
            'start_time' => Carbon::parse($this->nikah_date.' '.$this->start_time)->setTimezone(auth()->user()->timezone != null ? auth()->user()->timezone : 'UTC')->format('h:i A'),
            'start_time_simple' => $nikah_start_time,
            'end_time' => Carbon::parse($this->nikah_date.' '.$this->end_time)->format('h:i A'),
            'end_time_simple' => $nikah_end_time,
            'start_date' => Carbon::parse($this->nikah_date)->toFormattedDateString(),
            'assigned_imam' => $this->assignedImam ? fullName($this->assignedImam->first_name,$this->assignedImam->last_name) : 'N/A',
            'assingned_witness' => $this->witnesses ? WitnessResource::collection($this->witnesses) : '',
            'certificate_url' => $certifcate ? $certifcate->system_certificate : '',
            'system_certificate' => $this->system_certificate != null ? public_path($this->system_certificate) : '',
            'govt_certificate' => $this->govt_certificate != null ? public_path('uploads/certificates/').$this->govt_certificate : '',
            'link' => $this->zoom_join_url,
            'zoom_start_url' => $this->zoom_start_url,
            'is_validated' => $this->is_validated,
            'imam_id' => $this->imam_id,
            'recorded_link' => $this->zoom_recorded_link,
            'groom_detail' => $this->history? new UserResource($this->history->groom): null,
            'bride_detail' => $this->history ? new UserResource($this->history->bride): null,
            'history' => $this->history ? $this->history : null
        ];
    }
}
