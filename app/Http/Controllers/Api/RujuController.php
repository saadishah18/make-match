<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruju;
use App\Models\Talaq;
use App\Repositories\Interfaces\RujuInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RujuController extends Controller
{
    protected $rujuInterface;

    public function __construct(RujuInterface $rujuInterface)
    {
        $this->rujuInterface = $rujuInterface;
    }

    public function applyRuju(Request $request)
    {
        try{
            $request->validate(['talaq_id' => ['required', 'exists:talaqs,id']]);
            return $this->rujuInterface->applyRuju($request);
        }catch(\Exception $ex){
           return Api::response([],$ex->getMessage(),500);
        }

    }
    public function acceptRujuRequest(Request $request)
    {
        try{
            $request->validate(['ruju_id' => ['required', 'exists:rujus,id']]);
            if(Str::lower(Auth::user()->gender) == 'male'){
                return $this->rujuInterface->acceptRujuRequest($request);
            }
            return Api::response([],'Female can not accept ruju request',403);
        }catch(\Exception $ex){
            return Api::response([],$ex->getMessage(),500);
        }

    }

    public function rejectRujuRequest(Request $request)
    {
        try{
            $request->validate(['ruju_id' => ['required', 'exists:rujus,id']]);
            if(Str::lower(Auth::user()->gender) == 'male'){
                return $this->rujuInterface->rejectRujuRequest($request);
            }
            return Api::response([],'Female can only apply ruju',403);
        }catch(\Exception $ex){
            return Api::response([],$ex->getMessage(),500);
        }

    }
}

