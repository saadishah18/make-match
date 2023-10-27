<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MyServicesInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;

class MyServicesController extends Controller
{

    protected $my_services;

    public function __construct(MyServicesInterface $myServices)
    {
        $this->my_services = $myServices;
    }

    public function userOwnServices(){
        try {
            $response = $this->my_services->userOwnServices();
            return Api::response($response);
        }catch (\Exception $exception){
            dd($exception->getMessage(),$exception->getLine(),$exception->getFile());
        }
    }

}
