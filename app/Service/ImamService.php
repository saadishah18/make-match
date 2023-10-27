<?php

namespace App\Service;

use App\Models\NikahTimeTable;
use App\Models\TimeTableSlot;
use App\Models\User;
use Carbon\Carbon;

class ImamService
{
    public static function Imams(){
        $imams = User::whereHas('roles',function ($q) {
            $q->where('role_id',2);
        })->withCount('imamNikahs')->get();

        return $imams;
    }

    public static function ActiveImams(){
        $imams = User::whereHas('roles',function ($q) {
            $q->where('role_id',2);
        })->where('is_active',1)->get();
        return $imams;
    }

    public static function imamTimeTable($id){
        $timetables = TimeTableSlot::where('imam_id', $id)->select(['start_time','end_time','imam_id'])->get();
        $result_array = [];

        $times_array = [];
        $available_dates   = [];
        $dateAndtime = [];
        foreach ($timetables as $index => $item) {
            $startTime = Carbon::parse($item->start_time)->setTimezone($item->imamDetail->timezone);
            $endTime = Carbon::parse($item->end_time)->setTimezone($item->imamDetail->timezone);
            $times_array[] = [
                'start_time' => $startTime->toTimeString(),
                'end_time' => $endTime->toTimeString(),
            ];
            $dateAndtime['start_date_time'][] = Carbon::parse($item->start_time)->setTimezone(auth()->user()->timezone)->format('Y-m-d\TH:i:s');
            $dateAndtime['end_date_time'][] = Carbon::parse($item->end_time)->setTimezone(auth()->user()->timezone)->format('Y-m-d\TH:i:s');
        }

        $result_array = [
            'imam_id'=> $id,
            'shift_time2' => $times_array,
            'shift_dates' => $dateAndtime,
        ];

//        dd($result_array);



//        $dates_array['off_dates'] = [];
//        if($timetables){
//
//            $shift_time = array_values((array)json_decode($timetable->shift_time)->shift_time);
//            $shift_dates = array_keys((array)json_decode($timetable->shift_time)->shift_time);
//
//            if(!empty($shift_time)){
//                foreach ($shift_time as $item) {
////                dd($item->start_time);
//                    $start = Carbon::parse($item->start_time)->setTimezone($timetable->imamDetail->timezone);
//                    $end = Carbon::parse($item->end_time)->setTimezone($timetable->imamDetail->timezone);
//
//                    $carbonObjects[] = [
//                        'start_time' => $start,
//                        'end_time' => $end,
//                    ];
//
//                    $carbonObject2[] = [
//                        'start_time' => $item->start_time,
//                        'end_time' => $item->end_time,
//                    ];
//                }
//
//                $result_array = [
//                    'id'=> $timetable->id,
//                    'imam_id'=> $timetable->imam_id,
//                    'shift_time' => $carbonObjects,
//                    'shift_time2' => $carbonObject2,
//                    'shift_dates' => $shift_dates,
//                ];
//            }
//        }
//        dd($result_array);
        return $result_array;

    }
}
