<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Service\web\TalaqService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TalaqController extends Controller
{
    protected $talaq_service;
    public function __construct(TalaqService $service)
    {
        $this->talaq_service = $service;
    }

    public function index(Request $request){
        try {
            return Inertia::render('admin/talaq/Talaq',[
                'talaqs' => function () use ($request){
                   return $this->talaq_service->talaqListing($request);
                }
            ]);
        }catch (\Exception $exception){

        }
    }
}
