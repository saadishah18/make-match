<?php

namespace App\Providers;

use App\Http\Resources\RujuResource;
use App\Repositories\DashboardRepository;
use App\Repositories\ImamManagementRepository;
use App\Repositories\Interfaces\DashboardInterface;
use App\Repositories\Interfaces\ImamManagementInterface;
use App\Repositories\Interfaces\KhuluInterface;
use App\Repositories\Interfaces\MyActivityInterface;
use App\Repositories\Interfaces\MyServicesInterface;
use App\Repositories\Interfaces\NikahInterface;
use App\Repositories\Interfaces\NikahManagementInterface;
use App\Repositories\Interfaces\RujuInterface;
use App\Repositories\Interfaces\TalaqInterface;
use App\Repositories\Interfaces\UserInterface;
use App\Repositories\Interfaces\WitnessInterface;
use App\Repositories\KhuluRepository;
use App\Repositories\MyActivityRepository;
use App\Repositories\MyServicesRepository;
use App\Repositories\NikahManagementRepository;
use App\Repositories\NikahRepository;
use App\Repositories\RujuRepository;
use App\Repositories\TalaqRepository;
use App\Repositories\UserRepository;
use App\Repositories\WitnessManagementRepository;
use App\Service\Facades\Api;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Api', Api::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {
        Inertia::share('flash', function(){
            return [
                'message' => Session::get('message')
            ];
        });
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(DashboardInterface::class, DashboardRepository::class);
        $this->app->bind(NikahInterface::class, NikahRepository::class);
        $this->app->bind(MyServicesInterface::class, MyServicesRepository::class);
        $this->app->bind(MyActivityInterface::class, MyActivityRepository::class);
        $this->app->bind(ImamManagementInterface::class, ImamManagementRepository::class);
        $this->app->bind(NikahManagementInterface::class, NikahManagementRepository::class);
        $this->app->bind(TalaqInterface::class, TalaqRepository::class);
        $this->app->bind(RujuInterface::class, RujuRepository::class);
        $this->app->bind(WitnessInterface::class, WitnessManagementRepository::class);
        $this->app->bind(KhuluInterface::class, KhuluRepository::class);

    }
}
