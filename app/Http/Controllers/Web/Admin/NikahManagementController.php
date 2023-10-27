<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\NikahResource;
use App\Http\Resources\PaginationResource;
use App\Models\Nikah;
use App\Repositories\Interfaces\NikahManagementInterface;
use App\Repositories\NikahManagementRepository;
use App\Service\Facades\Api;
use App\Traits\ZoomV2Trait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class NikahManagementController extends Controller
{
    use ZoomV2Trait;
    protected $nikah_management_interface;


    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;


    public function __construct(NikahManagementInterface $interface)
    {
        $this->nikah_management_interface = $interface;
        /*dd(auth()->user());
        if(auth()->user()->roles->first()->name == 'imam'){
            auth()->logout();
        }*/
    }

    public function index(Request $request){
        try {
            $nikahs = $this->nikah_management_interface->nikahListing($request);
            return Inertia::render('admin/nikahmanagement/NikahManagement', [
                'nikahs' => function () use ($nikahs) {
                    return NikahResource::collection($nikahs);
                },
//                'pagination' => function () use ($nikahs) {
//                    return new PaginationResource($nikahs);
//                },
            ]);

        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function detail($id){
        try {
            return Inertia::render('admin/nikahdetails/NikahDetails', [
                'nikah_detail' => function () use ($id) {
                    return $this->nikah_management_interface->nikahDetail($id);
                },
            ]);
        } catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function getImamsforAssiging(Request $request){
        try {
            $imams = $this->nikah_management_interface->getImams($request);
            if(isset($imams['status']) &&  $imams['status'] == 422){
                return Api::error($imams['message']);
            }
            return Api::response(['imams' => $imams,'nikah_id' => $request->nikah_id],'Available Imam list');
        }catch (\Exception $exception){
            dd($exception->getMessage(), $exception->getFile(), $exception->getLine());
            return Api::server_error($exception);
        }
    }

    public function assignImamToNikah(Request $request){
        try {
            $update = $this->nikah_management_interface->assignImam($request);
            if($update['status'] ==  422){
                return Api::error($update['message']);
            }
            $nikah = Nikah::find($request->nikah_id);

            $data_to_pass_zoom_meeting=[
                'topic' => 'Nikah Meeting on Zoom',
                'type' => 2, // Scheduled Meeting
                'pre_schedule' => true,
                'duration' => 15,
                'agenda' => 'Nikah meeting in presence of witnesses',
                'timezone' => config('app.timezone'),
                'host_video' => true,
                'participant_video' => 1,
                'start_time' => $nikah->nikah_date.' '.$nikah->start_time,
                'end_date_time' => $nikah->nikah_date.' '.$nikah->start_time,
            ];

            $zoom_meeting_detail = $this->create($data_to_pass_zoom_meeting,$request->imam_id);

            if($zoom_meeting_detail['status'] == 201){
                $zoom_body = $zoom_meeting_detail['data']->json();
                $nikah->zoom_start_url = $zoom_body['start_url'];
                $nikah->zoom_join_url = $zoom_body['join_url'];
                $nikah->zoom_host_id = $zoom_body['host_id'];
            }

            $nikah->zoom_meeting_response = $zoom_meeting_detail['data'];
            $nikah->update();

            if($zoom_meeting_detail['status'] != 201){
                Log::error($zoom_meeting_detail);
                return Api::error('Imam Assigned but meeting did not created, Contact Admin');
            }
            if($update['status'] == 200){
               return Api::response([],'Imam Assigned successfully');
            }
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function getWitnessToAssign(Request $request){
        try {
            $response = $this->nikah_management_interface->getWitnessToAssign($request);
            if(isset($response['witness'])){
                return Api::response(['witnesses' => $response['witness']],$response['message']);
            }else{
                return Api::response([],$response['message']);
            }
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function assignWitnessToNikah(Request $request){
        try {
            $witnesses = $this->nikah_management_interface->assignWitnessToNikah($request);
            if($witnesses){
                return Api::response([],'Witness Assigned successfully');
            }else{
                return Api::error('Something went wrong');
            }
        }catch (\Exception $exception){
//            dd($exception->getMessage());
            return Api::server_error($exception);
        }
    }

    public function eventListings(Request $request){
        try {

            $nikahs = $this->nikah_management_interface->calenderNikahs($request);
            return Inertia::render('admin/eventscalendar/EventsCalendar', [
                'nikahs' => function () use ($nikahs) {
                    return NikahResource::collection($nikahs);
                }
            ]);
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
