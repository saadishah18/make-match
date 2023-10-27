<?php

namespace App\Repositories;

use App\Mail\NotifyMe;
use App\Models\ContactEmail;
use App\Models\User;
use App\Service\Facades\Api;
use Illuminate\Support\Facades\Mail;

class HomeRepository
{
    public function storeContacts($request){
        $store = ContactEmail::create($request->all());
        if($store){
            return true;
        }else{
            return false;
        }
    }

    public function notifyMe($request){
//        dd($request->all());
        $mail = Mail::to($request['email'])->send(new NotifyMe($request['email']));
        if($mail){
            return Api::response('','Mail sent successfully');
        }else{
            return Api::error('Something Went wrong');
        }
    }
}
