<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TalaqInterface;
use App\Service\Facades\Api;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TalaqController extends Controller
{
    protected $talaqinterface;

    public function __construct(TalaqInterface $talaqinterface)
    {
        $this->talaqinterface = $talaqinterface;
    }

    public function applyTalaq(Request $request)
    {
        try {
            if (Str::lower(Auth::user()->gender) == 'male') {
                return $this->talaqinterface->applyTalaq($request);
            }
            return Api::response([], 'Female should apply for khulu');
        } catch (Exception $exception) {
           return  Api::server_error($exception);
        }
    }

    public function addPregnancyDetail(Request $request){
        try{
            $pregnancy_detail = $this->talaqinterface->addPregnancyDetail($request);
            return Api::response($pregnancy_detail,'Date Added');
        }catch (Exception $exception){
            return Api::server_error($exception);
        }
    }
}
