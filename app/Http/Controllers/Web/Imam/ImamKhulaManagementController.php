<?php

namespace App\Http\Controllers\Web\Imam;

use App\Http\Controllers\Controller;
use App\Repositories\KhuluRepository;
use App\Service\Facades\Api;
use App\Service\web\KhuluService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImamKhulaManagementController extends Controller
{
    protected $khulu_repository;

    public function __construct(KhuluService $repository)
    {
        $this->khulu_repository = $repository;
    }

    public function index(Request $request){
        try {
            return Inertia::render('imam/khula/Khula', [
                'khulus' => function () use ($request) {
                    return $this->khulu_repository->khulaAssignedToImam($request, auth()->id());
                }
            ]);
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function validateKhulu(Request $request){
        try {
            $response = $this->khulu_repository->validateKhulu($request);
            return Api::response($response,'Khulu validated successfully');
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
    public function rejectKhulu(Request $request){
        try {
            $response = $this->khulu_repository->rejectKhulu($request);
            return Api::response($response,'Khulu rejected successfully');
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
