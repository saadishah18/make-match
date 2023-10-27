<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MyActivityInterface;
use App\Repositories\Interfaces\MyServicesInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use PHPUnit\TextUI\Exception;

class MyActivityController extends Controller
{
    protected $my_activities;

    public function __construct(MyActivityInterface $myActivities)
    {
        $this->my_activities = $myActivities;
    }

    public function index()
    {
        try {
            $response = $this->my_activities->myActivities();
            return Api::response($response);

        } catch (\Exception $exception) {
            return Api::response(null ,$exception->getMessage(),500);

        }
    }

    public function acceptInvitation(Request $request){
        try {
           return $this->my_activities->acceptInvitation($request);
        }catch (Exception $exception){
            Api::error($exception->getMessage());
        }
    }
}
