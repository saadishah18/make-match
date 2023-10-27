<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khulu;
use App\Models\Nikah;
use App\Models\Payments;
use App\Service\Facades\Api;
use App\Service\ImamService;
use App\Service\web\KhuluService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class KhuluController extends Controller
{
    protected $khulu_service;

    public function __construct(KhuluService $service)
    {
        $this->khulu_service = $service;
    }

    public function index(Request $request){
        try {
            return Inertia::render('admin/khula/Khula', [
                'khulus' => function () use ($request) {
                    return $this->khulu_service->khuluListing($request);
                },
            ]);
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
            return Api::response($exception->getMessage());
        }
    }

    public function getAllActiveImams(Request $request){
        try {
            $imams = ImamService::ActiveImams()/*->where('id','!=',$request->imam_id)*/;
            return Api::response(['imams' => $imams,'nikah_id' => $request->nikah_id],'Available Imam list');

        }catch (\Exception $exception){
            Log::info($exception->getMessage());
            return Api::server_error($exception);
        }
    }

    public function assignImamToKhulu(Request $request){
        try {
            $update = $this->khulu_service->assignImamToKhulu($request);
            if($update['status'] ==  422){
                return Api::error($update['message']);
            }
            if($update['status'] == 200){
                return Api::response([],'Imam Assigned successfully');
            }
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
            return Api::server_error($exception);
        }
    }


    public function khulaWebHook(Request $request)
    {
        try{
            $this->khulu_service->updateKhuluPaymentStatus($request);
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
            return response('error', 500);
        }
    }
}
