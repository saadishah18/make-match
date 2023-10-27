<?php

namespace App\Repositories;

use App\Models\NikahTimeTable;
use App\Models\TimeTableSlot;
use App\Service\ImamService;
use App\Service\NikahRelatedService;
use Carbon\Carbon;
use Ramsey\Uuid\Type\Time;

class ImamTimeTableRepository
{

    public function getTimeTable($id)
    {
        $imam_time_table = ImamService::imamTimeTable($id);
        return $imam_time_table;
    }


    /*    public function storeTimeTable($request){
            $imam_id = auth()->id();
            $week_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            $on_days['on_days'] = $request->onDays;
            $off_days['off_days'] = $request->offDays;
            $on_dates['on_dates'] = $request->onDates;
            $off_dates['off_dates'] = $request->offDates;

            $shift_time = explode('-',$request->shiftTime);

            for ($i=0; $i < count($week_days); $i++){
                if(in_array($week_days[$i],$on_days['on_days'])){
                    $start_of_day = Carbon::parse($shift_time[0]);
                    $end_of_day = Carbon::parse($shift_time[1]);
                    $shift_time_store['shift_time'][$week_days[$i]]['start_time'] = $start_of_day->format('H:i:s');
                    $shift_time_store['shift_time'][$week_days[$i]]['end_time'] = $end_of_day->format('H:i:s');

                }
            }
            $check_time_table_exists = NikahTimeTable::where('imam_id',$imam_id)->first();
            if($check_time_table_exists){
                $save_data = [
    //                'imam_id' => $imam_id,
                    'on_days' => json_encode($on_days),
                    'off_days' => json_encode($off_days),
                    'on_dates' => json_encode($on_dates),
                    'off_dates' => json_encode($off_dates),
                    'shift_time' => json_encode($shift_time_store),

                ];
                $create_time_table = NikahTimeTable::where('imam_id', $imam_id)->update($save_data);
                NikahRelatedService::makeonOfDates($imam_id);
                NikahRelatedService::saveImamAvaliableDates($imam_id);
                NikahRelatedService::makeSlotsForImam($imam_id);
            }else{
                $save_data = [
                    'imam_id' => $imam_id,
                    'on_days' => json_encode($on_days),
                    'off_days' => json_encode($off_days),
                    'on_dates' => json_encode($on_dates),
                    'off_dates' => json_encode($off_dates),
                    'shift_time' => json_encode($shift_time_store),

                ];
                $create_time_table = NikahTimeTable::create($save_data);
                NikahRelatedService::makeonOfDates($imam_id);
                NikahRelatedService::saveImamAvaliableDates($imam_id);
                NikahRelatedService::makeSlotsForImam($imam_id);
            }

            return $create_time_table;
        }*/

    public function storeTimeTableOLD($request)
    {
        $imam_id = auth()->id();
        $start_of_day = $request->startTime;
        $end_of_day = $request->endTime;
        $available_start_date = Carbon::parse($request->start_date);
        $available_end_date = Carbon::parse($request->end_date);

        dd($available_end_date,$available_start_date);

        $week_day_name = $available_start_date->dayName;

        if($available_start_date == $available_end_date){
            $shift_time_store['shift_time'][$request->start_date]['start_time'] = $start_of_day;
            $shift_time_store['shift_time'][$request->start_date]['end_time'] = $end_of_day;
//            dd($shift_time_store);
            $check_time_table_exists = NikahTimeTable::where('imam_id', $imam_id)->first();
            if ($check_time_table_exists) {
                $previous_shift_time = json_decode($check_time_table_exists['shift_time']);
                $previous_shift_time_array = (array)$previous_shift_time;
                foreach ($previous_shift_time as $index => $item) {
                    $time_array = (array)($item);
                    foreach ($time_array as $key => $time) {
                        if ($item != $request->date) {
                            $old_array = (array)$previous_shift_time_array["shift_time"];
                            $old_array[$request->start_date] = [
                                'start_time' => $start_of_day,
                                'end_time' => $end_of_day,
                            ];
                            $shift_time_store['shift_time'] = $old_array;
                            $save_data = [
                                'shift_time' => json_encode($shift_time_store),
                            ];
                            $create_time_table = NikahTimeTable::where('imam_id', $imam_id)->update($save_data);
                            NikahRelatedService::makeonOfDates($imam_id, $request);
                            NikahRelatedService::saveImamAvaliableDates($imam_id);
                            NikahRelatedService::makeSlotsForImam($imam_id);
                        } else {
                            $shift_time_store['shift_time'][$request->start_date]['start_time'] = $start_of_day;
                            $shift_time_store['shift_time'][$request->start_date]['end_time'] = $end_of_day;
                            $save_data = [
                                'shift_time' => json_encode($shift_time_store),
                            ];
                            $create_time_table = NikahTimeTable::where('imam_id', $imam_id)->update($save_data);
                            NikahRelatedService::makeonOfDates($imam_id, $request);
                            NikahRelatedService::saveImamAvaliableDates($imam_id);
                            NikahRelatedService::makeSlotsForImam($imam_id);
                        }
                    }
                }
            } else {
                $save_data = [
                    'imam_id' => $imam_id,
                    'shift_time' => json_encode($shift_time_store),
                ];
                $create_time_table = NikahTimeTable::create($save_data);
                NikahRelatedService::makeonOfDates($imam_id, $request);
                NikahRelatedService::saveImamAvaliableDates($imam_id);
                NikahRelatedService::makeSlotsForImam($imam_id);
            }
        }
        else{
            $dates = [$request->start_date, $request->end_date];
            foreach ($dates as $key => $date){
                if ($key === 0) {
                    $shift_time_store['shift_time'][$date]['start_time'] = $start_of_day;
                    $shift_time_store['shift_time'][$date]['end_time'] = '00:00'; // Set end_time to 00:00
                } else {
                    $shift_time_store['shift_time'][$date]['start_time'] = '00:00';
                    $shift_time_store['shift_time'][$date]['end_time'] = $end_of_day;
                }
            }

            $check_time_table_exists = NikahTimeTable::where('imam_id', $imam_id)->first();
            if ($check_time_table_exists) {
                $previous_shift_time = json_decode($check_time_table_exists['shift_time']);
                $previous_shift_time_array = (array)$previous_shift_time;
                foreach ($previous_shift_time as $index => $item) {
                    $time_array = (array)($item);
                    foreach ($time_array as $key => $time) {
                        if ($item != $request->date) {
                            $old_array = (array)$previous_shift_time_array["shift_time"];
                            foreach ($dates as $key => $date){
                                if ($key === 0) {
                                    $old_array[$date] = [
                                        'start_time' => $start_of_day,
                                        'end_time' => '00:00',
                                    ];
                                }else{
                                    $old_array[$date] = [
                                        'start_time' => '00:00',
                                        'end_time' => $end_of_day,
                                    ];
                                }

                            }

                            $shift_time_store['shift_time'] = $old_array;
                            $save_data = [
                                'shift_time' => json_encode($shift_time_store),
                            ];
                            $create_time_table = NikahTimeTable::where('imam_id', $imam_id)->update($save_data);
//                            dd($create_time_table);
                            NikahRelatedService::makeonOfDates($imam_id, $request);
                            NikahRelatedService::saveImamAvaliableDates($imam_id);
                            NikahRelatedService::makeSlotsForImam($imam_id);
                        } else {
//                            $shift_time_store['shift_time'][$request->date]['start_time'] = $start_of_day;
//                            $shift_time_store['shift_time'][$request->date]['end_time'] = $end_of_day;
                            $save_data = [
                                'shift_time' => json_encode($shift_time_store),
                            ];
                            $create_time_table = NikahTimeTable::where('imam_id', $imam_id)->update($save_data);
                            NikahRelatedService::makeonOfDates($imam_id, $request);
                            NikahRelatedService::saveImamAvaliableDates($imam_id);
                            NikahRelatedService::makeSlotsForImam($imam_id);
                        }
                    }
                }
            } else {
                $save_data = [
                    'imam_id' => $imam_id,
                    'shift_time' => json_encode($shift_time_store),
                ];
                $create_time_table = NikahTimeTable::create($save_data);
                NikahRelatedService::makeonOfDates($imam_id, $request);
                NikahRelatedService::saveImamAvaliableDates($imam_id);
                NikahRelatedService::makeSlotsForImam($imam_id);
            }
        }

        return $create_time_table;
    }

    public function storeTimeTable($request)
    {
        $imam_id = auth()->id();
        $imam = auth()->user();
        $start_date_time = changeDatetimeZone($request->start_date_time, $imam->timezone);
        $end_date_time = changeDatetimeZone($request->end_date_time, $imam->timezone);
//        dd($start_date_time, $end_date_time);
        $shift = TimeTableSlot::whereDate('start_time',Carbon::parse($start_date_time)->toDateString())->whereDate('end_time',Carbon::parse($end_date_time)->toDateString())->where('imam_id',$imam_id)->first();
        if($shift){
            $shift->start_time= $start_date_time;
            $shift->end_time= $end_date_time;
            $shift->update();
        }else{
            $shift = TimeTableSlot::create([
                'imam_id' => $imam_id,
                'start_time' => $start_date_time,
                'end_time' => $end_date_time,
            ]);
        }

        NikahRelatedService::makeonOfDates($imam_id, $request);
        NikahRelatedService::saveImamAvaliableDates($imam_id);
//        NikahRelatedService::makeSlotsForImam($imam_id);
        NikahRelatedService::makeSlotsForImam($imam_id, $request);

        return $shift;
    }

}
