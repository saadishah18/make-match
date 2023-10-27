<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Service\web\RujuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RujuController extends Controller
{
    protected $ruju_service;

    public function __construct(RujuService $service)
    {
        $this->ruju_service = $service;
    }

    public function index(Request $request)
    {
        try {
            return Inertia::render('admin/ruju/Ruju', [
                'rujus' => function () use ($request) {
                    return $this->ruju_service->rujuService($request);
                },
            ]);
        }catch (\Exception $exception){

        }
    }
}
