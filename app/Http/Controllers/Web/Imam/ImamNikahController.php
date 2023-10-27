<?php

namespace App\Http\Controllers\Web\Imam;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Repositories\ImamNikahRepository;
use App\Service\Facades\Api;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PharIo\Version\Exception;

class ImamNikahController extends Controller
{
    protected $imam_nikah_service;

    public function __construct(ImamNikahRepository $imamnikahrepo)
    {
        $this->imam_nikah_service = $imamnikahrepo;
    }

    public function index(Request $request)
    {
        try {
            return Inertia::render('imam/nikahmanagement/NikahManagement', [
                'nikahs' => function () use ($request) {
                    return $this->imam_nikah_service->getImamSpecificNikah($request);
                },
            ]);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }

    public function detail($id){
        try {
            return Inertia::render('imam/nikahdetails/NikahDetails', [
                'nikah_detail' => function () use ($id) {
                    return $this->imam_nikah_service->nikahDetail($id);
                },
            ]);
        } catch (\Exception $exception){
            Api::error($exception->getMessage());
        }
    }

    public function validateNikah(Request $request){
        try {

            $nikah_detail = $request->nikah_detail;
//            $image_url = $request->image_url;
            $check_old_certificate = Certificate::where('activity_id',$nikah_detail['nikah_id'])->where('activity_model','App/Nikah')->first();
            $nikah_history = NikahDetailHistory::where('nikah_id',$nikah_detail['nikah_id'])->first();

            if($check_old_certificate != null && ( $check_old_certificate['system_certificate'] != "" || $check_old_certificate['system_certificate'] != null)){
               return Api::error('System Certificate Already Exists');
            }
            elseif($check_old_certificate != null && $check_old_certificate['system_certificate'] == null){
                $check_old_certificate->system_certificate = $check_old_certificate->uploadImage($request,'image_url',true);
                $check_old_certificate->update();
//                return Redirect::route('imam.nikahmanagement')->with('success','Certificate uploaded successfully');
                return Api::error('Certificate uploaded successfully');
            }else{
                $save_certificate = new Certificate();
                $save_certificate->male_id =$nikah_history->male_id;
                $save_certificate->female_id =$nikah_history->female_id;
                $save_certificate->activity_id =$nikah_history->nikah_id;
                $save_certificate->activity_name =  'Nikah Validate';
                $save_certificate->activity_model = 'App/Nikah';
                $save_certificate->system_certificate = $save_certificate->uploadImage($request,'image_url',true);
                $save_certificate->save();
                $nikah = Nikah::find($nikah_detail['nikah_id']);
                $nikah->is_validated = 1;
                $nikah->update();
                $nikah_history->current_status = 'Nikahfied';
                $nikah_history->update();
                return Api::response($save_certificate,'Certificate Saved Successfully');
            }
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function uploadCertificates(Request $request){
        try {
//            $nikah_detail = $request->nikah_detail;
            $image = $request->image;
            $check_old_certificate = Certificate::where('activity_id',$request->nikah_id)->where('activity_model','App/Nikah')->first();
            $nikah_history = NikahDetailHistory::where('nikah_id',$request->nikah_id)->first();

            if($check_old_certificate != null && $check_old_certificate['govt_certificate'] != null){
                return Redirect::route('imam.nikahmanagement')->with('error','Govet Certificate Already Uploaded');

            }elseif($check_old_certificate != null && $check_old_certificate['govt_certificate'] == null){
                $check_old_certificate->govt_certificate = $check_old_certificate->uploadImage($request,'image',true);
                $check_old_certificate->update();
                return Redirect::route('imam.nikahmanagement')->with('success','Certificate uploaded successfully');
            }
            else{
                $save_certificate = new Certificate();
                $save_certificate->male_id =$nikah_history->male_id;
                $save_certificate->female_id =$nikah_history->female_id;
                $save_certificate->activity_id =$nikah_history->nikah_id;
                $save_certificate->activity_name =  'Nikah Validate';
                $save_certificate->activity_model = 'App/Nikah';
                $save_certificate->govt_certificate = $save_certificate->uploadImage($request,'image',true);
                $save_certificate->save();
                return Redirect::route('imam.nikahmanagement')->with('success','Certificate uploaded successfully');
            }
        }catch (\Exception $exception){
            return Redirect::route('imam.nikahmanagement')->with('error',$exception->getMessage());

        }
    }

    public function storeRecordedLink(Request $request)
    {
        try {
            $nikah = Nikah::find($request->nikah_id);
            $nikah->zoom_recorded_link = $request->recorded_link;
            $nikah->update();
            return Api::response([], 'Link saved successfully');
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }
}
