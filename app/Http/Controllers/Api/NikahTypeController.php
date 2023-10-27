<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NikahServiceResource;
use App\Http\Resources\NikahTypeResource;
use App\Repositories\Interfaces\NikahInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Exception;

class NikahTypeController extends Controller
{
    protected $nikah_interface;

    public function __construct(NikahInterface $nikahinterface)
    {
        $this->nikah_interface = $nikahinterface;
    }

    public function index()
    {
        $response = $this->nikah_interface->nikahTypes();
        return Api::response(NikahTypeResource::collection($response),trans('response.nikah_type_listing'));
    }

    public function services(Request $request)
    {
        try {
            $response = $this->nikah_interface->nikahServices($request);
//        dd($response);
            /* if(isset($response['error'])){
                return Api::validation_errors();
             }*/
            return Api::response($response);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }

    }
}
