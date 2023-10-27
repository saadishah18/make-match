<?php

namespace App\Service\web;

use App\Http\Resources\TalaqResource;
use App\Models\Talaq;

class TalaqService
{
    public function talaqListing($request){
        $talaqs = Talaq::all();
        return TalaqResource::collection($talaqs);

    }
}
