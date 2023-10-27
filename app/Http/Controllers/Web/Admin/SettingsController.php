<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortalSetting;
use App\Repositories\SettingsRepository;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SettingsController extends Controller
{
    protected $settings_repo;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings_repo = $settings;
    }

    public function storeVat(Request $request){
        try {
            if($request['vat'] != ''){
                return Inertia::render('admin/vat/Vat', [
                    'vat' => function () use ($request) {
                        return  $this->settings_repo->getVat($request);
                    },
                ])->with('success','Vat saved successfully');
            }
            return Inertia::render('admin/vat/Vat', [
                'vat' => function () use ($request) {
                    return  $this->settings_repo->getVat($request);
                },
            ]);

        }catch (\Exception $exception){

        }
    }


    public function getPrivacyPolicy(Request $request){
        return Inertia::render('admin/vat/PrivacyPolicy', [
            'privacy_policy' => function () use ($request) {
                return  $this->settings_repo->privacyPolicy($request);
            },
            'terms_and_conditions' => function () use ($request) {
                return $this->settings_repo->termsConditions($request);
            },
        ])->with('success','Updated successfully');
    }

    public function privacyPolicy(Request $request){
        $request->validate([
            'privacy_policy' => 'max:30000',
            'terms_and_conditions' => 'max:30000',
        ]);
//        if($request['privacy_policy'] != '' || $request['terms_and_conditions'] != ''){
//            return Inertia::render('admin/vat/PrivacyPolicy', [
//                'privacy_policy' => function () use ($request) {
//                        return  $this->settings_repo->privacyPolicy($request);
//                },
//                'terms_and_conditions' => function () use ($request) {
//                    return $this->settings_repo->termsConditions($request);
//                },
//            ])->with('success','Settings saved successfully');
//        }
        return Inertia::render('admin/vat/PrivacyPolicy', [
            'privacy_policy' => function () use ($request) {
                return  $this->settings_repo->privacyPolicy($request);
            },
            'terms_and_conditions' => function () use ($request) {
                return $this->settings_repo->termsConditions($request);
            },
        ])->with('success','Updated successfully');
    }

    public function servicesOffered(){
        return Inertia::render('admin/vat/services', [
            'services' => function ()  {
                return  $this->settings_repo->servicesOffered();
            },
        ]);
    }

    public function updateServicePrice(Request $request){
        try {
            return $this->settings_repo->updateServicePrice($request);
//            return Redirect::back()->with('success','Imam assigned to a future nikah. Can not be deleted');
        }catch (\Throwable $throwable){
            Api::server_error($throwable);
        }
    }

    public function nikahTypes(){
        return Inertia::render('admin/vat/NikahTypes', [
            'types' => function ()  {
                return  $this->settings_repo->nikahTypes();
            },
        ]);
    }

    public function updateNikahType(Request $request){
//        dd($request->all());
        try {
            return $this->settings_repo->updateNikahType($request);
//            return Redirect::back()->with('success','Imam assigned to a future nikah. Can not be deleted');
        }catch (\Throwable $throwable){
            Api::server_error($throwable);
        }
    }

}
