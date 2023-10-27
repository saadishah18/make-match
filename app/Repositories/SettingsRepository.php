<?php

namespace App\Repositories;

use App\Http\Resources\NikahServiceResource;
use App\Http\Resources\NikahTypeResource;
use App\Models\NikahType;
use App\Models\PortalSetting;
use App\Models\Services;
use App\Models\User;
use App\Service\Facades\Api;
use Illuminate\Support\Facades\Hash;

class SettingsRepository
{
    public function getVat($request){
        if(isset($request['vat']) && $request['vat'] != null){
            $vat = PortalSetting::updateOrCreate(['name' =>'vat'], [
                'value' => $request->vat,
            ]);
            return $vat->value;
        }
        $vat = PortalSetting::where('name','vat')->first();
        if($vat){
            $vat_value = $vat->value;
            return $vat_value;
        }

        return $vat;
    }

    public function privacyPolicy($request = null){
//        if(isset($request['privacy_policy']) && $request['privacy_policy'] != null){
//            $obj = PortalSetting::updateOrCreate(['name' =>'privacy_policy'], [
//                'value' => $request->privacy_policy,
//            ]);
//            r eturn $obj->value;
//        }
        if(isset($request['privacy_policy'])){
            $obj = PortalSetting::updateOrCreate(['name' =>'privacy_policy'], [
                'value' => $request->privacy_policy,
            ]);
            return $obj->value;
        }
        else {
            $obj = PortalSetting::where('name', 'privacy_policy')->first();
            if ($obj) {
                return $obj->value;
            }
//            return null;
        }
//
    }

    public function termsConditions($request = null){
//        if(isset($request['terms_and_conditions']) && $request['terms_and_conditions'] != ''){
//            $obj = PortalSetting::updateOrCreate(['name' =>'terms_and_conditions'], [
//                'value' => $request->terms_and_conditions,
//            ]);
//            return $obj->value;
//        }
//        $obj = PortalSetting::where('name','terms_and_conditions')->first();
//        if($obj){
//            return $obj->value;
//        }
//        return null;
        if (isset($request['terms_and_conditions'])) {
            $obj = PortalSetting::updateOrCreate(['name' => 'terms_and_conditions'], [
                'value' => $request->terms_and_conditions,
            ]);
            return $obj->value;
        } else {
            $obj = PortalSetting::where('name', 'terms_and_conditions')->first();
            if ($obj) {
                return $obj->value;
            }
//            return null;
        }
    }

    public function servicesOffered(){
        $service = Services::all();
        return NikahServiceResource::collection($service);
    }

    public function updateServicePrice($request){
        $service = Services::find($request->id);
        $service->price = $request->price;
        $service->update();
        return Api::response($service,'Price updated successfully');
    }

    public function nikahTypes(){
        $types = NikahType::all();
        return NikahTypeResource::collection($types);
    }

    public function updateNikahType($request){
        $service = NikahType::find($request->id);
        $service->price = $request->price;
//        $service->description = $request->description;
        $service->update();
        return Api::response($service,'Data updated successfully');
    }

}
