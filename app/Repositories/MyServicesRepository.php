<?php

namespace App\Repositories;

use App\Http\Resources\KhuluResource;
use App\Http\Resources\NikahResource;
use App\Http\Resources\RujuResource;
use App\Http\Resources\TalaqResource;
use App\Models\Khulu;
use App\Models\Ruju;
use App\Models\Talaq;
use App\Repositories\Interfaces\MyServicesInterface;
use App\Traits\NikahTrait;

class MyServicesRepository implements MyServicesInterface
{
    use NikahTrait;
    public function userOwnServices(){
        $user = auth()->user();
        $nikah_detail = $this->nikahDetail($user);
        $my_services = [];
        $my_services['nikah'] = NikahResource::collection($nikah_detail);
//        $my_services['nikah']['services'] = [];
        $talaqs = $this->getTalaqs($user);
        $ruju = Ruju::where('male_id',$user->id)->orWhere('partner_id',$user->id)->get();
        $my_services['talaq'] = TalaqResource::collection($talaqs);
        $my_services['ruju'] = RujuResource::collection($ruju);
        $khulu = Khulu::where('male_id',$user->id)->orWhere('partner_id',$user->id)->get();
        $my_services['khulu'] = KhuluResource::collection($khulu);
        if(checkGender($user) == 'female'){
            //$my_services['khulu'] = [];
        }
        return $my_services;
    }

    public function getTalaqs($user){
        return Talaq::where('male_id',$user->id)->orWhere('partner_id',$user->id)->get();
    }

}
