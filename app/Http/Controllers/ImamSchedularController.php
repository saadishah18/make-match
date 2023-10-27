<?php

namespace App\Http\Controllers;

use App\Models\NikahTimeTable;
use App\Models\TimeTableSlot;
use App\Repositories\ImamTimeTableRepository;
use App\Service\Facades\Api;
use App\Service\NikahRelatedService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Psy\Util\Json;

class ImamSchedularController extends Controller
{
    protected $time_table_service;

    public function __construct(ImamTimeTableRepository $repository)
    {
        $this->time_table_service = $repository;
    }

    public function index(Request $request){
        try {
            return Inertia::render('imam/eventscalendar/EventsCalendarNew', [
                'timetable' => function () {
                    return $this->time_table_service->getTimeTable(auth()->id()) ?? [];
                },
            ]);
        }catch (\Exception $exception){
//            dd($exception->getMessage(), $exception->getLine(), $exception->getFile());
            return Inertia::render('imam/eventscalendar/EventsCalendarNew')->with('error',$exception->getMessage());

        }
    }

    public function create(){
        return Inertia::render('imam/eventscalendar/create');
    }

    public function saveTimeTable(Request $request){
        try {
            $store_time_table = $this->time_table_service->storeTimeTable($request);
            return Api::response($store_time_table,'Schedule Updated Successfully');
        }catch (\Exception $exception){

            return Api::server_error($exception);
        }
    }

    public function deleteScheduleDateOLD(Request $request){
        try {
            $data = $request->shift;
            $time_table = NikahTimeTable::where('imam_id',auth()->id())->first();
            $start_date_time = Carbon::parse($data['start'],auth()->user()->timezone)->setTimezone('UTC')->toDateTimeString();
            $end_date_time = Carbon::parse($data['end'],auth()->user()->timezone)->setTimezone('UTC')->toDateTimeString();
            foreach ($data['dates'] as $key => $date){
                $shift_detail = $data;
                $date_to_delete = $data['dates'][$key];

                $previous_shift_time = json_decode($time_table['shift_time']);
                $previous_slots = json_decode($time_table['defined_slots']);
                $available_on_dates = json_decode($time_table['available_on_dates']);
                $new_available_dates = array_diff($available_on_dates, [$date_to_delete]);

                $new_shift_time = (array) $previous_shift_time->shift_time;
                unset($new_shift_time[$date_to_delete]);
                $new_slots = (array) $previous_slots;
                unset($new_slots[$date_to_delete]);
                $shift_time['shift_time'] = $new_shift_time;
                $time_table->shift_time = json_encode($shift_time);
                $time_table->available_on_dates = json_encode(array_values($new_available_dates));
                $time_table->defined_slots = json_encode($new_slots);
//            dd($time_table);
                $time_table->update();
                $check_null = json_decode($time_table->available_on_dates);
                if(empty($check_null)){
                    NikahTimeTable::find($time_table->id)->delete();
                    return Api::response([],'Deleted Successfully');
                }
            }
            return Api::response($time_table->refresh(),'Deleted Successfully');

        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }


    public function deleteScheduleDate(Request $request){
        try {
            $data = $request->shift;
            $start_date_time = Carbon::parse($data['start'],auth()->user()->timezone)->setTimezone('UTC');
            $end_date_time = Carbon::parse($data['end'],auth()->user()->timezone)->setTimezone('UTC');

            TimeTableSlot::where('imam_id',auth()->id())
                ->where('start_time', $start_date_time->toDateTimeString())
                ->where('end_time',$end_date_time->toDateTimeString())->delete();

            NikahRelatedService::makeonOfDates(auth()->id(), $request);
            NikahRelatedService::saveImamAvaliableDates(auth()->id());
            NikahRelatedService::makeSlotsForImam(auth()->id(), $request);

        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
