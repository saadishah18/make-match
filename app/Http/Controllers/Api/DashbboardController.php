<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\DashboardInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;

class DashbboardController extends Controller
{
    protected $dashboard_interface;

    public function __construct(DashboardInterface $interface)
    {
        $this->dashboard_interface = $interface;
    }

    public function dashboardRecords()
    {
        $response = $this->dashboard_interface->dashboardData();
        return Api::response($response);
    }
}
