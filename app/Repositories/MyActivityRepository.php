<?php

namespace App\Repositories;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\KhuluResource;
use App\Http\Resources\NikahResource;
use App\Http\Resources\RujuResource;
use App\Models\Khulu;
use App\Models\Nikah;
use App\Models\Ruju;
use App\Models\User;
use App\Models\Walli;
use App\Models\Witness;
use App\Repositories\Interfaces\MyActivityInterface;
use App\Service\Facades\Api;

class MyActivityRepository implements MyActivityInterface
{
    public function myActivities(): array
    {
        $user = auth()->user();
        $nikahs = Nikah::whereHas('wali', function ($wali) use ($user) {
            $wali->where('user_as_wali_id', $user->id);
        })->orWhereHas('witnesses', function ($witneses) use ($user) {
            $witneses->where('user_as_witness_id', $user->id);
        })->with(['currentUserAsWali', 'currentUserAsWitness'])->get();
        $myActivities = [];
        $myActivities['nikah'] = ActivityResource::collection($nikahs);
        $ruju = Ruju::where('male_id',$user->id)->orWhere('partner_id',$user->id)->get();
        $myActivities['ruju'] = RujuResource::collection($ruju);
        $khulu = Khulu::where('male_id',$user->id)->orWhere('partner_id',$user->id)->get();
        $myActivities['khulu'] = KhuluResource::collection($khulu);
        return $myActivities;
    }

    public function acceptInvitation($request){
        if($request['user_type'] == 'wali'){
            $update_record = Walli::where('user_as_wali_id',auth()->id())->where('nikah_id',$request->nikah_id)->update(['is_invitation_accepted'=> $request->is_accept]);
        }
        if($request['user_type'] == 'witness'){
            $update_record = Witness::where('user_as_witness_id',auth()->id())->where('nikah_id',$request->nikah_id)->update(['is_invitation_accepted'=> $request->is_accept]);
        }
        return Api::response([],'Updated Successfully');
    }
}
