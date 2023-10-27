<?php

namespace App\Service;

use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahTimeTable;
use App\Models\ServiceObtained;
use App\Models\Services;
use App\Models\TimeTableSlot;
use App\Models\User;
use App\Models\Walli;
use App\Models\Witness;
use App\Notifications\InviteNotification;
use Carbon\Carbon;
use http\Env\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class NikahRelatedService
{

    public static function onOfDates()
    {

        $imams = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })->get(['id']);

        $six_month_dates_array = NikahRelatedService::getSixMonthDates();

        foreach ($imams as $imam_index => $imam) {
            $get_time_table_detail = NikahTimeTable::where('imam_id', $imam->id)->first();
            $on_days = $get_time_table_detail->on_days['on_days'];

            $on_days_dates = NikahRelatedService::getOnDaysDates($on_days, $six_month_dates_array);

            $on_dates = $get_time_table_detail->on_dates['on_dates'];
            $final_on_dates = array_merge($on_days_dates, $on_dates);
            sort($final_on_dates);

            $off_days = $get_time_table_detail->off_days['off_days'];

            $of_days_dates = NikahRelatedService::getOfDaysDates($off_days, $six_month_dates_array);
            $off_dates = $get_time_table_detail->off_dates['off_dates'];
            $final_off_dates = array_merge($of_days_dates, $off_dates);
            sort($final_off_dates);
            $dates_array = [];
//            $unique_on_dates[] = array_unique($final_on_dates);
//            $unique_off_dates = array_unique($final_off_dates);
            $dates_array['on_dates'] = $final_on_dates;
            $dates_array['off_dates'] = $final_off_dates;

            $get_time_table_detail->dates_of_imam = $dates_array;
            $get_time_table_detail->update();
        }
        echo "saved";
    }

    public static function makeonOfDatesOLD($imam_id, $request)
    {

        $six_month_dates_array = NikahRelatedService::getSixMonthDates($request);
//        foreach ($imams as $imam_index => $imam) {
        $get_time_table_detail = NikahTimeTable::where('imam_id', $imam_id)->first();

        $on_days = $get_time_table_detail->on_days['on_days'];


        $on_days_dates = NikahRelatedService::getOnDaysDates($on_days, $six_month_dates_array);
        $on_dates = $get_time_table_detail->on_dates['on_dates'];
        $final_on_dates = array_merge($on_days_dates, $on_dates);
        sort($final_on_dates);
        $final_on_dates = array_unique($final_on_dates);

        $off_days = $get_time_table_detail->off_days['off_days'];

        $of_days_dates = NikahRelatedService::getOfDaysDates($off_days, $six_month_dates_array);
        $off_dates = $get_time_table_detail->off_dates['off_dates'];
        $final_off_dates = array_merge($of_days_dates, $off_dates);
        sort($final_off_dates);
        $final_off_dates = array_unique($final_off_dates);
        $dates_array = [];
//            $unique_on_dates[] = array_unique($final_on_dates);
//            $unique_off_dates = array_unique($final_off_dates);
        $dates_array['on_dates'] = $final_on_dates;
        $dates_array['off_dates'] = $final_off_dates;

        $get_time_table_detail->dates_of_imam = $dates_array;
        $get_time_table_detail->update();
//        }
        return true;
    }


    public static function makeonOfDatesOLD1($imam_id, $request)
    {
        $get_time_table_detail = NikahTimeTable::where('imam_id', $imam_id)->first();

        $previous_dates_array = json_decode($get_time_table_detail['available_on_dates']);
        $previous_shifts = (array)json_decode($get_time_table_detail['shift_time']);
        if($previous_shifts != null){
            foreach ($previous_shifts as $index => $item) {
                $old_array = (array) $item;
                foreach ($old_array as $date_key => $value){
                    $dates_array['on_dates'][] = $date_key;
                }
            }
        }else{
            $available_start_date = Carbon::parse($request->start_date);
            $available_end_date = Carbon::parse($request->end_date);
            if ($available_start_date == $available_end_date) {
                $dates_array['on_dates'][] = $request->date;
            } else {
                $dates = [$request->start_date, $request->end_date];
                foreach ($dates as $date) {
                    $dates_array['on_dates'][] = $date;
                }
            }
        }
        $dates_array['off_dates'] = [];
        $get_time_table_detail->dates_of_imam = $dates_array;
        $get_time_table_detail->update();
        return true;
    }


    public static function makeonOfDates($imam_id, $request)
    {
        $previous_shifts = TimeTableSlot::where('imam_id',$imam_id)->orderBy('start_time')->get()->toArray();
        $dates_array['on_dates'] = [];
        foreach ($previous_shifts as $index => $item) {
            $startDate = date('Y-m-d',strtotime($item['start_time']));

            if(!in_array($startDate,$dates_array['on_dates'])){
                $dates_array['on_dates'][] = $startDate;
            }
            $endDate  = date('Y-m-d',strtotime($item['end_time']));
            if(!in_array($endDate,$dates_array['on_dates'])){
                $dates_array['on_dates'][] = $endDate;
            }
        }
        $dates_array['off_dates'] = [];

        NikahTimeTable::updateOrCreate(['imam_id' => $imam_id],[
            'imam_id' => $imam_id,
            'dates_of_imam' => json_encode($dates_array)
        ]);
        return true;
    }

    public static function getSixMonthDates()
    {
        $six_month_date = Carbon::now()->daysUntil(Carbon::now()->addMonths(3));
        $dates_array = [];
        foreach ($six_month_date as $date_index => $date) {
            $date_string = $date->toDateString();
            $dates_array[] = $date_string;
        }
        return $dates_array;
    }

    public static function getOnDaysDates($on_days, $six_month_dates_array)
    {
        $on_dates_array = [];
        foreach ($six_month_dates_array as $date_index => $date_string) {
            $day_string = Carbon::parse($date_string)->dayName;
            if (in_array($day_string, $on_days)) {
//                $on_dates_array[$day_string][] = $date_string;
                $on_dates_array[] = $date_string;
            }
        }
        return $on_dates_array;
    }

    public static function getOfDaysDates($off_days, $six_month_dates_array)
    {
        $of_dates_array = [];
        foreach ($six_month_dates_array as $date_index => $date_string) {
            $day_string = Carbon::parse($date_string)->dayName;
            if (in_array($day_string, $off_days)) {
                $of_dates_array[] = $date_string;
            }
        }
        return $of_dates_array;
    }

    public static function saveAvaliableDates()
    {
        $imams = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })->get(['id']);

        foreach ($imams as $imam_index => $imam) {
            $get_time_table_detail = NikahTimeTable::where('imam_id', $imam->id)->first();
            $dates_of_imam = json_decode($get_time_table_detail->dates_of_imam);
            $available_on_dates = array_diff($dates_of_imam->on_dates, $dates_of_imam->off_dates);
//            $available_on_dates = array_values($available_on_dates);
            $get_time_table_detail->available_on_dates = json_encode($available_on_dates);
            $get_time_table_detail->update();
        }
        echo "done";
    }

    public static function saveImamAvaliableDatesOLD($imam_id)
    {

//        foreach ($imams as $imam_index => $imam) {
        $get_time_table_detail = NikahTimeTable::where('imam_id', $imam_id)->first();
        $dates_of_imam = json_decode($get_time_table_detail->dates_of_imam);

        $available_on_dates = array_diff((array)$dates_of_imam->on_dates, (array)$dates_of_imam->off_dates);

//            $available_on_dates = array_values($available_on_dates);
        $get_time_table_detail->available_on_dates = json_encode($available_on_dates);
        $get_time_table_detail->update();
//        }
        return true;
    }

    public static function saveImamAvaliableDates($imam_id)
    {
        $get_time_table_detail = NikahTimeTable::where('imam_id', $imam_id)->first();
        $dates_of_imam = json_decode($get_time_table_detail->dates_of_imam);

        $available_on_dates = array_diff((array)$dates_of_imam->on_dates, (array)$dates_of_imam->off_dates);

        $get_time_table_detail->available_on_dates = json_encode($available_on_dates);
        $get_time_table_detail->update();
        return true;
    }

    public static function makeSlots()
    {

        $get_dates_details = NikahTimeTable::all();
        $time_slot_minutes = config('app.time_slot_minutes');

        $total_no_of_slots_in_an_hour = 60 / $time_slot_minutes;
        foreach ($get_dates_details as $detail_index => $detail) {
            $imam_available_dates = json_decode($detail->available_on_dates);
            $imam_shift_time = json_decode($detail->shift_time);
            $shift_days_array = collect($imam_shift_time->shift_time)->toArray();
            $days_array = array_keys($shift_days_array);

            // only including on days because we do not have any schedule for on dates
            $each_imam_slot = [];
            foreach ($imam_available_dates as $date_index => $date) {
//                $date_index = $detail->imam_id;
                $dayName = Carbon::parse($date)->dayName;
                if (in_array($dayName, $days_array)) {
                    $get_day_shift_time = $shift_days_array[$dayName];
                    /*  $total_shift_time_array[$date][$detail_index]['start_time'] = $get_day_shift_time->start_time;
                      $total_shift_time_array[$date][$detail_index]['end_time'] = $get_day_shift_time->end_time;*/
                    $day_start_time = $get_day_shift_time->start_time;
                    $day_end_time = $get_day_shift_time->end_time;

                    $total_no_of_working_hours_per_day = date('H', strtotime($day_end_time)) - date('H', strtotime($day_start_time));

                    $slot_start_time = Carbon::parse($date . $day_start_time);
                    $slot_end_time = Carbon::parse($date . $day_start_time)->addMinutes($time_slot_minutes);

                    for ($i = 1; $i <= $total_no_of_working_hours_per_day; $i++) {
                        for ($j = 0; $j < $total_no_of_slots_in_an_hour; $j++) {
                            if ($j > 0) {
                                $slot_start_time = $slot_start_time->addMinutes($time_slot_minutes);
                                $slot_end_time = $slot_end_time->addMinutes($time_slot_minutes);
                            }
                            $each_imam_slot[$date][] = [
                                'imam_id' => $detail->imam_id,
                                'start_time' => $slot_start_time->toTimeString(),
                                'end_time' => $slot_end_time->toTimeString(),
                            ];
                        }
                    }
                }
            }
            $detail->defined_slots = $each_imam_slot;
            $detail->update();
        }
        echo "done";
        // need to show every imam slot date wise with available dates data in seprate column
        // make a function get Imam time table it will give all settings of that imam with id.
//        $create_slots = TimeTableSlot::insert($save_slots);
    }

    public static function makeSlotsForImamOLD($imam_id)
    {

        $detail = NikahTimeTable::where('imam_id', $imam_id)->first();
        $time_slot_minutes = config('app.time_slot_minutes');

        $total_no_of_slots_in_an_hour = 60 / $time_slot_minutes;
        $imam_available_dates = json_decode($detail->available_on_dates);
        $imam_shift_time = json_decode($detail->shift_time);
        $shift_days_array = collect($imam_shift_time->shift_time)->toArray();
        $days_array = array_keys($shift_days_array);

        // only including on days because we do not have any schedule for on dates
        $each_imam_slot = [];
        $imam_available_dates = (array)$imam_available_dates;
        foreach ($imam_available_dates as $date_index => $date) {
//                $date_index = $detail->imam_id;
            $dayName = Carbon::parse($date)->dayName;
            if (in_array($dayName, $days_array)) {
                $get_day_shift_time = $shift_days_array[$dayName];
                /*  $total_shift_time_array[$date][$detail_index]['start_time'] = $get_day_shift_time->start_time;
                  $total_shift_time_array[$date][$detail_index]['end_time'] = $get_day_shift_time->end_time;*/
                $day_start_time = $get_day_shift_time->start_time;
                $day_end_time = $get_day_shift_time->end_time;


                $total_no_of_working_hours_per_day = date('H', strtotime($day_end_time)) - date('H', strtotime($day_start_time));
                $slot_start_time = Carbon::parse($date . $day_start_time);
                $slot_end_time = Carbon::parse($date . $day_start_time)->addMinutes($time_slot_minutes);

                for ($i = 0; $i < $total_no_of_working_hours_per_day; $i++) {
                    for ($j = 0; $j <= $total_no_of_slots_in_an_hour; $j++) {
                        if ($j > 0) {
                            $slot_start_time = $slot_start_time->addMinutes($time_slot_minutes);
                            $slot_end_time = $slot_end_time->addMinutes($time_slot_minutes);
                        }
                        $each_imam_slot[$date][] = [
                            'imam_id' => $detail->imam_id,
                            'start_time' => $slot_start_time->toTimeString(),
                            'end_time' => $slot_end_time->toTimeString(),
                        ];
                    }
                }
            }
        }
        $detail->defined_slots = $each_imam_slot;
        $detail->update();
        return true;
        // need to show every imam slot date wise with available dates data in seprate column
        // make a function get Imam timetable it will give all settings of that imam with id.
//        $create_slots = TimeTableSlot::insert($save_slots);
    }

    public static function makeSlotsForImamOLD2($imam_id)
    {

        $detail = NikahTimeTable::where('imam_id', $imam_id)->first();
        $time_slot_minutes = config('app.time_slot_minutes');

        $imam_available_dates = json_decode($detail->available_on_dates);
        $imam_shift_time = json_decode($detail->shift_time);
        $shift_days_array = collect($imam_shift_time->shift_time)->toArray();
        $days_array = array_keys($shift_days_array);


        // only including on days because we do not have any schedule for on dates
        $each_imam_slot = [];
        $imam_available_dates = (array)$imam_available_dates;
        $slots = [];
        foreach ($imam_available_dates as $date_index => $date) {

//            $dayName = Carbon::parse($date)->dayName;
            if (in_array($date, $days_array)) {
                $get_day_shift_time = $shift_days_array[$date];



                /*  $total_shift_time_array[$date][$detail_index]['start_time'] = $get_day_shift_time->start_time;
                  $total_shift_time_array[$date][$detail_index]['end_time'] = $get_day_shift_time->end_time;*/



                /*$total_no_of_working_hours_per_day = date('H', strtotime($day_end_time)) - date('H', strtotime($day_start_time));
                $slot_start_time = Carbon::parse($date . $day_start_time);
                $slot_end_time = Carbon::parse($date . $day_start_time)->addMinutes($time_slot_minutes);

                for ($i = 0; $i < $total_no_of_working_hours_per_day; $i++) {
                    for ($j = 0; $j < $total_no_of_slots_in_an_hour; $j++) {
                        if ($j > 0) {
                            $slot_start_time = $slot_start_time->addMinutes($time_slot_minutes);
                            $slot_end_time = $slot_end_time->addMinutes($time_slot_minutes);
                        }
                        $each_imam_slot[$date][] = [
                            'imam_id' => $detail->imam_id,
                            'start_time' => $slot_start_time->toTimeString(),
                            'end_time' => $slot_end_time->toTimeString(),
                        ];
                    }
                }*/

                $day_start_time = $get_day_shift_time->start_time;
                $day_end_time = $get_day_shift_time->end_time;


                // Calculate the total number of working hours per day
//                $total_no_of_working_hours_per_day = (strtotime($day_end_time) - strtotime($day_start_time)) / 3600;
//                $total_no_of_working_minutes = (strtotime($day_end_time) - strtotime($day_start_time)) / 60;



// Convert start time and end time to DateTime objects
                $start_time = \DateTime::createFromFormat('H:i', $day_start_time);
                $end_time = \DateTime::createFromFormat('H:i', $day_end_time);

// Calculate the total number of working minutes per day
                if ($start_time > $end_time) {
                    // If end time is on the next day, calculate the difference accordingly
                    $interval = $start_time->diff(new \DateTime('tomorrow 00:00'));
//                    dd($interval);
                    $total_no_of_working_minutes = ($interval->h * 60) + $interval->i;
                } else {
                    $interval = $start_time->diff($end_time);
                    $total_no_of_working_minutes = ($interval->h * 60) + $interval->i;
                }


//                if($date === '2023-09-05'){
//                    dd($start_time, $end_time,$total_no_of_working_minutes);
////                    dd($total_no_of_working_minutes,$slot_start_time,$slot_end_time,$total_slots);
//                }
                $slot_start_time = \Carbon\Carbon::parse($date . ' ' . $day_start_time);
                $slot_end_time = $slot_start_time->copy()->addMinutes($time_slot_minutes);

                $total_slots = $total_no_of_working_minutes / $time_slot_minutes;

                for ($i = 0; $i < $total_slots; $i++) {
                    // Generate slots for each 15-minute interval within the hour
//                    $total_no_of_slots_in_an_hour = 60 / $time_slot_minutes;

//                    for ($j = 0; $j < $total_no_of_slots_in_an_hour; $j++) {
                        $each_imam_slot = [
                            'imam_id' => $detail->imam_id,
                            'start_time' => $slot_start_time->toTimeString(),
                            'end_time' => $slot_end_time->toTimeString(),
                        ];

                        $slots[$date][] = $each_imam_slot;

                        // Move to the next slot
                        $slot_start_time->addMinutes($time_slot_minutes);
                        $slot_end_time->addMinutes($time_slot_minutes);
//                    }
                }
            }
        }
        $detail->defined_slots = $slots;
        $detail->update();
        return true;
        // need to show every imam slot date wise with available dates data in seprate column
        // make a function get Imam timetable it will give all settings of that imam with id.
//        $create_slots = TimeTableSlot::insert($save_slots);
    }

    public static function makeSlotsForImam($imam_id)
    {

        $detail = NikahTimeTable::where('imam_id', $imam_id)->first();
        $shift_details = TimeTableSlot::where('imam_id',$imam_id)->get();
        $time_slot_minutes = config('app.time_slot_minutes');

        $slots = [];
        foreach ($shift_details as $date_index => $shift_detail) {

            $total_slots = Carbon::parse($shift_detail->start_time)->diffInMinutes($shift_detail->end_time) / $time_slot_minutes;

            for ($i = 0; $i < $total_slots; $i++) {

                $slot_start_timestamp = Carbon::parse($shift_detail->start_time)->addMinutes($time_slot_minutes * $i);

                $endTimeslotMinutes = $time_slot_minutes * ($i+1);

                $endTimeMinutes = Carbon::parse($shift_detail->start_time)->addMinutes($endTimeslotMinutes)->minute;

                if ($endTimeMinutes == 0){

                    $endTimeslotMinutes -=1;
                }

                $each_imam_slot = [
                    'imam_id' => $detail->imam_id,
                    'start_time' => $slot_start_timestamp->toTimeString(),
                    'end_time' => Carbon::parse($shift_detail->start_time)->addMinutes($endTimeslotMinutes)->toTimeString(),
                ];

                $slots[$slot_start_timestamp->toDateString()][] = $each_imam_slot;

            }
        }
        $detail->defined_slots = $slots;
        $detail->update();
        return true;
    }

    public static function getAvailableDatesOfAllImams($start_date, $end_date, $type, $timezone)
    {
        $imams = User::whereHas('roles', function ($q) {
            $q->where('id', 2);
        })->get(['id','timezone']);
        $timezones = [];
        $available_on_dates = [];

        foreach ($imams as $imam_index => $imam) {
            $get_time_table_detail = NikahTimeTable::where('imam_id', $imam->id)->first();
            if ($get_time_table_detail) {
                $available_on_dates[] = json_decode($get_time_table_detail->available_on_dates);
                $timezones[] = $imam->timezone;
            }
        }
        $compiled_dates =[];
        foreach ($available_on_dates as $dates){
            foreach ($dates as $key =>  $date){
            $compiled_dates[] = $date;

            }
        }
        sort($compiled_dates);
        $compiled_dates = array_unique($compiled_dates);
        $final_dates = [];
        foreach ($compiled_dates as $i => $date) {
            $converted_date2 = '"' . $date . '"';
            $date_slots[] =  NikahTimeTable::selectRaw("JSON_UNQUOTE(JSON_EXTRACT(defined_slots, '$.$converted_date2')) as result")
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(defined_slots, '$.$converted_date2')) IS NOT NULL")
                ->get();

            foreach ($date_slots as $key => $slots_data) {
                if (!$slots_data->isEmpty()) {
                    foreach ($slots_data as $i => $slot_data){
                        $slots = json_decode($slot_data->result);
                        $slots_count = count($slots);

//                        if(isset($date_slots['result']) && !is_null($date_slots['result'])){
//                            $date_slots = json_decode($date_slots['result']);
                            $converted_start_date = Carbon::parse($date.' '.$slots[0]->start_time)->format('Y-m-d\TH:i:s.u\Z');;
                            $converted_end_date = Carbon::parse($date.' '.$slots[$slots_count-1]->start_time)->format('Y-m-d\TH:i:s.u\Z');;
                            $final_dates[] = $converted_start_date;
                            $final_dates[] = $converted_end_date;
//                        }
                    }
                }
            }
        }

        $unique_array = array_unique($final_dates);
        $sorted_index_dates_array = array_values($unique_array);
        sort($sorted_index_dates_array);

        $calander_dates = [];
        // adding user timezone for passing his current date to skip 24 hours slots
        $today_date = Carbon::now()->setTimezone($timezone);
        $one_week_date = $today_date->addDays(7)->format('Y-m-d\TH:i:s.u\Z');

        $start_date = Carbon::parse($start_date)->setTimezone($timezone);
        $end_date = Carbon::parse($end_date)->setTimezone($timezone);


        $current_date = now()->timezone($timezone);

       foreach ($sorted_index_dates_array as $key => $date) {
            $carbon_date = Carbon::parse($date);
            $date = $carbon_date->toDateString();

            // Calculate the difference in hours between $carbon_date and $current_date
            $hours_difference = $carbon_date->diffInHours($current_date);

            if (
                $date > $start_date->toDateString() &&
                $date < $end_date->toDateString() &&
                $hours_difference >= 24
            ) {
                if ($type == 1 && $date > $current_date->toDateString() && $date > $one_week_date) {
                    $calander_dates[] = $carbon_date;
                }
                if ($type == 2 && $date > $current_date->toDateString() && $carbon_date <= $one_week_date) {
                    $calander_dates[] = $carbon_date;
                }
            }
        }

     /* foreach ($sorted_index_dates_array as $key => $date) {
            $carbon_date = $date;
            $date = Carbon::parse($date)->toDateString();
            dd($carbon_date, $date);
            if (($date > $start_date->toDateString()) && ($date < $end_date->toDateString())) {
                if ($type == 1 && ($date > now()->timezone($timezone)->toDateString()) && $date > $one_week_date) {
                    $calander_dates[] = $carbon_date;
                }
                if ($type == 2 && ($date > now()->timezone($timezone)->toDateString()) && $carbon_date <= $one_week_date) {
                    $calander_dates[] = $carbon_date;
                }
            }
        }*/

        return $calander_dates;
    }

    public static function getDateSlotsOLD($nikah_date = null, $timezone ='utc'){

        $date = '"' . $nikah_date . '"';
        $imamTimeZones = [];
        $get_single_date_slots = NikahTimeTable::selectRaw("JSON_EXTRACT(defined_slots, '$.$date') as result")->first();

        $get_single_date_slots = json_decode($get_single_date_slots['result']);


        if(isset($get_single_date_slots[0]) && $get_single_date_slots[0]->start_time == '00:00:00'){
            $final_available_slots = [];
            $previous_date = Carbon::parse($nikah_date)->subDay()->toDateString();

            if($previous_date != $nikah_date){
                $previous_date = '"' . $previous_date . '"';
                $dates_array = [$previous_date, $date];

                foreach ($dates_array as $date_index => $value){
                    $get_slots_from_time_table = NikahTimeTable::selectRaw("JSON_EXTRACT(defined_slots, '$.$value') as result, imam_id")->get();
                    $cleanedDate = trim($dates_array[$date_index], '"'); // Remove double quotes
                    foreach ($get_slots_from_time_table as $key => $slot_data) {
                        $total_slots = [];
                        if ($slot_data->result != null) {
                            $slots = json_decode($slot_data->result);
                            foreach ($slots as $slot_detail) {
                                if(property_exists($slot_detail, "start_time")){
                                    $total_slots['start_time'][] = $slot_detail->start_time;
                                }
                                if(property_exists($slot_detail, "end_time")){
                                    $total_slots['end_time'][] = $slot_detail->end_time;
                                }
                            }
                        }
                    }

                    if (empty($total_slots)) {
                        $return_array['message'] = trans('response.no_slot');
                        $return_array['error'] = true;
                        $return_array['status'] = 422;
                        return $return_array;
                    }


                    $duplicate_start_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['start_time']);
                    $duplicate_end_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['end_time']);


                    $already_book_slots = Nikah::where('nikah_date', $cleanedDate)->selectRaw('count(id) as slot_count , nikahs.*')->groupBy('start_time')->get();
                    $booked_slots = [];

                    if (!$already_book_slots->isEmpty()) {
                        foreach ($already_book_slots as $key => $slot) {
                            $booked_slots['start_time'][] = $slot->start_time;
                            $booked_slots['end_time'][] = $slot->end_time;
                            $booked_slots['slot_count'][] = $slot->slot_count;
                        }

                        $booked_slots_count_array = [];

                        foreach ($booked_slots['start_time'] as $key => $booked_slot) {
                            $booked_slots_count_array['start_time'][$booked_slot] = $booked_slots['slot_count'][$key];
                        }

                        foreach ($booked_slots['end_time'] as $key => $booked_slot) {
                            $booked_slots_count_array['end_time'][$booked_slot] = $booked_slots['slot_count'][$key];
                        }


                        $slots_after_removing_booked_slots = NikahRelatedService::slotsAfterRemovingBookedSlots($duplicate_start_slots_array, $duplicate_end_slots_array, $booked_slots_count_array);

                        $available_slots['start_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['start_time'] : [];
                        $available_slots['end_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['end_time'] : [];
                    } else {
                        $unique_start_time = array_unique($total_slots['start_time']);
                        $unique_end_time = array_unique($total_slots['end_time']);
                        $available_slots['start_time'] = $unique_start_time;
                        $available_slots['end_time'] = $unique_end_time;
                    }

                  if (empty($available_slots['start_time'])) {
                        $return_array['message'] = trans('response.no_slot');
                        $return_array['error'] = true;
                        $return_array['status'] = 422;
                        return $return_array;
                    }

                    $convert_slots_to_time_stamps = [];
                    foreach ($available_slots['start_time'] as $key => $time) {
                        $time = $cleanedDate . ' ' . $time;
//                        $convert_slots_to_time_stamps['start_time'][] = Carbon::parse($time)->shiftTimezone($default_time)->setTimezone($timezone);
                        $convert_slots_to_time_stamps['start_time'][] = Carbon::parse($time);
                    }

                    foreach ($available_slots['end_time'] as $key => $time) {
                        $time = $cleanedDate . ' ' . $time;
//                        $convert_slots_to_time_stamps['end_time'][] = Carbon::parse($time)->shiftTimezone($default_time)->setTimezone($timezone);
                        $convert_slots_to_time_stamps['end_time'][] = Carbon::parse($time);
                    }
                    $combined_date_slots[] = $convert_slots_to_time_stamps;
                }

                $start_dates = [];
                $end_dates = [];
                foreach ($combined_date_slots as $index => $slots_array){
                    if($index){
                        $start_dates = array_merge( $combined_date_slots[$index-1]['start_time'], $slots_array['start_time']);
                        $end_dates = array_merge($combined_date_slots[$index-1]['end_time'], $slots_array['end_time']);
                    }
                }
                return ['start_time' => $start_dates, 'end_time' => $end_dates];
            }
        }
        else{
            $get_slots_from_time_table = NikahTimeTable::selectRaw("JSON_EXTRACT(defined_slots, '$.$date') as result,imam_id")
                ->whereRaw("deleted_at IS NULL")->get();
            $total_slots = [];
            foreach ($get_slots_from_time_table as $key => $slot_data) {

                if ($slot_data->result != null) {
                    $slots = json_decode($slot_data->result);
                    foreach ($slots as $slot_detail) {
                        if(property_exists($slot_detail, "start_time")){
                            $startTime = $nikah_date.' '.$slot_detail->start_time;
                            $carbonStartTime = Carbon::parse($startTime);
                            $convertedStartTime = $carbonStartTime->setTimezone($timezone);
                            $total_slots['start_time'][] = $convertedStartTime->toTimeString();
                        }
                        if(property_exists($slot_detail, "end_time")){

                            $endTime = $nikah_date.' '.$slot_detail->start_time;
                            $carbonEndTime = Carbon::parse($endTime);
                            $convertedEndTime = $carbonEndTime->setTimezone($timezone);
                            $total_slots['end_time'][] = $convertedEndTime->toTimeString();
                        }
                    }
                }
                if($slot_data->imamDetail){
                    $imamTimeZones[] = $slot_data->imamDetail->timezone;
                }
            }

            if (empty($total_slots)) {
                $return_array['message'] = trans('response.no_slot');
                $return_array['error'] = true;
                $return_array['status'] = 422;
                return $return_array;
            }

            sort($total_slots['start_time']);
            sort($total_slots['end_time']);

            $duplicate_start_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['start_time']);
            $duplicate_end_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['end_time']);

            $already_book_slots = Nikah::where('nikah_date', $nikah_date)->selectRaw('count(id) as slot_count , nikahs.*')->groupBy('start_time')->get();
            $booked_slots = [];

            if (!$already_book_slots->isEmpty()) {
                foreach ($already_book_slots as $key => $slot) {
                    $startTime = $nikah_date.' '.$slot->start_time;
                    $carbonStartTime = Carbon::parse($startTime);
                    $convertedStartTime = $carbonStartTime->setTimezone($timezone);

                    $endTime = $nikah_date.' '.$slot->end_time;
                    $carbonEndTime = Carbon::parse($endTime);
                    $convertedEndTime = $carbonEndTime->setTimezone($timezone);

                    $booked_slots['start_time'][] = $convertedStartTime->toTimeString();
                    $booked_slots['end_time'][] = $convertedEndTime->toTimeString();
                    $booked_slots['slot_count'][] = $slot->slot_count;
                }

                $booked_slots_count_array = [];

                foreach ($booked_slots['start_time'] as $key => $booked_slot) {
                    $booked_slots_count_array['start_time'][$booked_slot] = $booked_slots['slot_count'][$key];
                }

                foreach ($booked_slots['end_time'] as $key => $booked_slot) {
                    $booked_slots_count_array['end_time'][$booked_slot] = $booked_slots['slot_count'][$key];
                }

                $slots_after_removing_booked_slots = NikahRelatedService::slotsAfterRemovingBookedSlots($duplicate_start_slots_array, $duplicate_end_slots_array, $booked_slots_count_array);

                $available_slots['start_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['start_time'] : [];
                $available_slots['end_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['end_time'] : [];
            }
            else {
                $unique_start_time = array_unique($total_slots['start_time']);
                $unique_end_time = array_unique($total_slots['end_time']);
                $available_slots['start_time'] = $unique_start_time;
                $available_slots['end_time'] = $unique_end_time;
            }

            if (empty($available_slots['start_time'])) {
//            return errorMessage(trans('response.no_slot'), true, 422);
                $return_array['message'] = trans('response.no_slot');
                $return_array['error'] = true;
                $return_array['status'] = 422;
                return $return_array;
            }

            $convert_slots_to_time_stamps = [];
            foreach ($available_slots['start_time'] as $key => $time) {
                $time = $nikah_date . ' ' . $time;
                $convert_slots_to_time_stamps['start_time'][] = $time;
            }

            foreach ($available_slots['end_time'] as $key => $time) {
                $time = $nikah_date . ' ' . $time;
                $convert_slots_to_time_stamps['end_time'][] =$time;
            }
            $convertedTimeZones = [];

            foreach ($imamTimeZones as $imamTimeZone) {
                $convertedStartTimes = [];
                $convertedEndTimes = [];
                foreach ($convert_slots_to_time_stamps['start_time'] as $startTime) {
                    $carbonStartTime = Carbon::parse($startTime, $imamTimeZone);
                    $convertedStartTime = $carbonStartTime->setTimezone($timezone);
                    if(Carbon::parse($nikah_date)->toDateString() === $convertedStartTime->toDateString()) {
//                        $convertedStartTimes[] = $convertedStartTime->format('Y-m-d\TH:i:s.uP');
                        $convertedStartTimes[] = $convertedStartTime->format('Y-m-d\TH:i:s.u');
                    }
                }

                foreach ($convert_slots_to_time_stamps['end_time'] as $endTime) {
                    $carbonEndTime = Carbon::parse($endTime, $imamTimeZone);
                    $convertedEndTime = $carbonEndTime->setTimezone($timezone);

                    if(Carbon::parse($nikah_date)->toDateString() === $convertedEndTime->toDateString()){
                        $convertedEndTimes[] = $convertedEndTime->format('Y-m-d\TH:i:s.u');
                    }

//                    $convertedEndTimes[] = $convertedEndTime->toDateTimeString();
                }

                $convertedTimeZones = [
                    'start_time' => $convertedStartTimes,
                    'end_time' => $convertedEndTimes,
                ];
            }

            sort($convertedStartTimes);
            $user = User::find(auth()->id());
            $user->timezone = $timezone;
            $user->update();
            return $convertedTimeZones;
        }
    }
    public static function getDateSlots($nikah_date = null, $timezone ='utc'){

       $total_slots = NikahTimeTable::getSlotsArrayOnDate($nikah_date);

        if (empty($total_slots)) {
            $return_array['message'] = trans('response.no_slot');
            $return_array['error'] = true;
            $return_array['status'] = 422;
            return $return_array;
        }

        sort($total_slots['start_time']);
        sort($total_slots['end_time']);

        $duplicate_start_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['start_time']);
        $duplicate_end_slots_array = NikahRelatedService::getDuplicateSlotsCount($total_slots['end_time']);

        $already_book_slots = Nikah::where('nikah_date', $nikah_date)->selectRaw('count(id) as slot_count , nikahs.*')->groupBy('start_time')->get();
        $booked_slots = [];
        if (!$already_book_slots->isEmpty()) {
            foreach ($already_book_slots as $key => $slot) {
                $startTime = $nikah_date . ' ' . $slot->start_time;
                $carbonStartTime = Carbon::parse($startTime);
                $convertedStartTime = $carbonStartTime;

                $endTime = $nikah_date . ' ' . $slot->end_time;
                $carbonEndTime = Carbon::parse($endTime);
                $convertedEndTime = $carbonEndTime;

                $booked_slots['start_time'][] = $convertedStartTime->toDateTimeString();
                $booked_slots['end_time'][] = $convertedEndTime->toDateTimeString();
                $booked_slots['slot_count'][] = $slot->slot_count;
            }

            $booked_slots_count_array = [];

            foreach ($booked_slots['start_time'] as $key => $booked_slot) {
                $booked_slots_count_array['start_time'][$booked_slot] = $booked_slots['slot_count'][$key];
            }

            foreach ($booked_slots['end_time'] as $key => $booked_slot) {
                $booked_slots_count_array['end_time'][$booked_slot] = $booked_slots['slot_count'][$key];
            }
//            Log::info(['booked slots' => $booked_slots_count_array]);

            $slots_after_removing_booked_slots = NikahRelatedService::slotsAfterRemovingBookedSlots($duplicate_start_slots_array, $duplicate_end_slots_array, $booked_slots_count_array);

            $available_slots['start_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['start_time'] : [];
            $available_slots['end_time'] = count($slots_after_removing_booked_slots) ? $slots_after_removing_booked_slots['end_time'] : [];
        } else {
            $unique_start_time = array_unique($total_slots['start_time']);
            $unique_end_time = array_unique($total_slots['end_time']);
            $available_slots['start_time'] = $unique_start_time;
            $available_slots['end_time'] = $unique_end_time;
        }

        if (empty($available_slots['start_time'])) {
            $return_array['message'] = trans('response.no_slot');
            $return_array['error'] = true;
            $return_array['status'] = 422;
            return $return_array;
        }

        $convertedTimeZones = [];
        $convertedStartTimes = [];
        $convertedEndTimes = [];

        $current_date = now()->setTimezone($timezone);
       /*foreach ($available_slots['start_time'] as $startTime) {
            $carbonStartTime = Carbon::parse($startTime);
            $startDateTime = Carbon::parse($startTime);
            $hours_difference = $carbonStartTime->setTimezone($timezone)->diffInHours($current_date);
            if (Carbon::parse($nikah_date)->toDateString() === $carbonStartTime->setTimezone($timezone)->toDateString()) {
                $convertedStartTimes[] = $startDateTime->format('Y-m-d\TH:i:s.u\Z');
            }
            $convertedTimeZones['start_time'] = $convertedStartTimes;
        }*/


       foreach ($available_slots['start_time'] as $key => $startTime) {
            $carbonStartTime = Carbon::parse($startTime);
            $startDateTime = Carbon::parse($startTime);
            $endTime = $available_slots['end_time'][$key];
           $carbonEndTime = Carbon::parse($endTime);
           $endDateTime = Carbon::parse($endTime);

            // Skip 24 hours earlier slots
            if ($carbonStartTime->setTimezone($timezone)->lt(Carbon::now()->timezone($timezone)->addHours(24))) {
                continue;
            }

            // Add a check to get slots after 24 hours
//            if ($carbonStartTime->setTimezone($timezone)->gt(Carbon::now()->timezone($timezone))) {
                $convertedStartTimes[] = $startDateTime->format('Y-m-d\TH:i:s.u\Z');
                $convertedEndTimes[] = $endDateTime->format('Y-m-d\TH:i:s.u\Z');
//            }
        }

      /* foreach ($available_slots['end_time'] as $endTime) {
           $carbonEndTime = Carbon::parse($endTime);
           $endDateTime = Carbon::parse($endTime);

            // Skip 24 hours earlier slots
            if ($carbonEndTime->setTimezone($timezone)->lt(Carbon::now()->timezone($timezone)->addHours(6))) {
                continue;
            }

            // Add a check to get slots after 24 hours
            if ($carbonEndTime->setTimezone($timezone)->gt(Carbon::now()->timezone($timezone))) {
                $convertedEndTimes[] = $endDateTime->format('Y-m-d\TH:i:s.u\Z');
            }
        }*/

        $convertedTimeZones['start_time'] = $convertedStartTimes;
        $convertedTimeZones['end_time'] = $convertedEndTimes;

       /* foreach ($available_slots['end_time'] as $endTime) {
            $carbonEndTime = Carbon::parse($endTime);
            $endDateTime = Carbon::parse($endTime);
            $hours_difference = $carbonEndTime->setTimezone($timezone)->diffInHours($current_date);

            if (Carbon::parse($nikah_date)->toDateString() === $carbonEndTime->setTimezone($timezone)->toDateString()) {
                $convertedEndTimes[] = $endDateTime->format('Y-m-d\TH:i:s.u\Z');
            }
            $convertedTimeZones['end_time'] = $convertedEndTimes;
        }*/


        if (empty($convertedTimeZones['start_time'])) {
            $return_array['message'] = trans('response.no_slot');
            $return_array['error'] = true;
            $return_array['status'] = 422;
            return $return_array;
        }

        $user = User::find(auth()->id());
        $user->timezone = $timezone;
        $user->update();
        return $convertedTimeZones;
    }

    public static function getAvailableImamOnDateAndTime($imam = null, $nikah_date = null, $start_date_time = null,$nikah_detail=null){
        $start_time = Carbon::parse($start_date_time)->toTimeString();

        $total_slots = NikahTimeTable::getSlotsArrayOnDate($nikah_date, $imam->id);

        if (!isset($total_slots['start_time']) && !isset($total_slots['end_time'])) {
            return null;
        }

        sort($total_slots['start_time']);
        sort($total_slots['end_time']);


//       Log::info(['start_time' => $nikah_date.' '.$start_time, 'total_slots_start_time' => $total_slots['start_time']]);
//       Log::info(['in check' => in_array($nikah_date.' '.$start_time, $total_slots['start_time'])]);
        if (in_array($nikah_date.' '.$start_time, $total_slots['start_time'])) {
            $already_book_slots = Nikah::where('nikah_date', $nikah_date)
                ->where('imam_id', $imam->id)
                ->where('start_time', $start_time)
                ->selectRaw('count(id) as slot_count , nikahs.*')
                ->groupBy('start_time')
                ->get();
            if ($already_book_slots->isEmpty()) {
                return $imam;
            }
        }
        return null;
    }

    public static function saveUserAsWali($request_data, $nikah,$password)
    {
        $wali_user = User::where('email', trim($request_data['wali_email']))->first();
        if ($wali_user == null) {
            $save_data = [
                'email' => $request_data['wali_email'],
                'password' => Hash::make($password),
                'active_role' => 'wali'
            ];
            $wali_user = User::create($save_data);
            $role = $wali_user->assignrole('user');
        }else{
            $wali_user->password = Hash::make($password);
            $wali_user->save();
        }
        $check_wali_status = Walli::where('nikah_id',$nikah->id)->where('user_as_wali_id',$wali_user->id)->first();
        if($check_wali_status != null && $check_wali_status->is_invitation_accepted == 0){
            $check_wali_status->is_invitation_accepted = 2;
            $check_wali_status->update();
        }else{
            $create_wali_record = [
                'invited_by' => isset($request_data['user_applied_nikah_id']) ? $request_data['user_applied_nikah_id'] : $request_data['user_id'],
                'user_as_wali_id' => $wali_user->id,
                'nikah_id' => $nikah->id,
                'is_invitation_accepted' => 2,
                'generated_password' => $password
            ];

            $user = Walli::create($create_wali_record);
        }
        return $wali_user;
    }

    public static function saveUserAsWitness($request_data, $email, $nikah, $password)
    {
        $witness_user = User::where('email', $email)->first();
        if ($witness_user == null) {
            $create_data = [
                'email' => $email,
                'password' => Hash::make($password),
                'active_role' => 'witness'
            ];
            $witness_user = User::create($create_data);
            $create_witness_record = [
                'invited_by' => isset($request_data['user_applied_nikah_id']) ? $request_data['user_applied_nikah_id'] : $request_data['user_id'],
                'user_as_witness_id' => $witness_user->id,
                'nikah_id' => $nikah->id,
                'is_invitation_accepted' => 2,
                'generated_password' => $password

            ];
            $role = $witness_user->assignrole('user');

            $save_data = Witness::create($create_witness_record);
            return $witness_user;
        }else{

            $check_witness = Witness::where('nikah_id',$nikah->id)->where('user_as_witness_id',$witness_user->id)->first();
            if($check_witness == null){
                $create_witness_record = [
                    'invited_by' => isset($request_data['user_applied_nikah_id']) ? $request_data['user_applied_nikah_id'] : $request_data['user_id'],
                    'user_as_witness_id' => $witness_user->id,
                    'nikah_id' => $nikah->id,
                    'is_invitation_accepted' => 2,
                    'generated_password' => $password

                ];
                $save_data = Witness::create($create_witness_record);
            }else{
                $check_witness->is_invitation_accepted = 2;
                $check_witness->save();
            }
            $witness_user->password = Hash::make($password);
            $witness_user->save();
            return $witness_user;
        }
    }

    public static function saveServiceObtainedInNikah($request_data, $nikah)
    {
        $service_ids = Services::whereIN('slug', $request_data['services'])->get()->pluck('id')->toArray();
        foreach ($service_ids as $id) {
            $service_obtained = [
                'nikah_id' => $nikah->id,
                'service_id' => $id,
            ];
            $save_services = ServiceObtained::create($service_obtained);
            if ($save_services->service->slug == 'nikah_with_wali') {
//                $partner_user->notify(new InviteNotification($offerData));
                $password = generateStrongPassword();
                $wali_user = NikahRelatedService::saveUserAsWali($request_data, $nikah,$password);
                $type = 'Wali';
                if ($wali_user) {
                    $wali_user->notify(new InviteNotification($request_data['wali_email'], $password, $nikah, $type));
                }
            }

            if ($save_services->service->slug == 'own_witness') {
                foreach ($request_data['witness_email'] as $key => $email) {
                    $password = generateStrongPassword();
                    $witness_user = NikahRelatedService::saveUserAsWitness($request_data, $email,$nikah,$password);
                    $witness_detail = Witness::where('user_as_witness_id',$witness_user->id)->first();
//                    sendInviteToWitness($email,$nikah,$witness_user, $witness_detail->generated_password);
                    $type = 'Witness';
                    if ($witness_user) {
                        $witness_user->notify(new InviteNotification($email,$password,$nikah ,$type));
                    }
                }
            }
        }
        return $request_data;
    }

    public static function saveNikahHistory($nikah, $male_id, $female_id)
    {
        $save_nikah_history = [
            'nikah_id' => $nikah->id,
            'male_id' => $male_id,
            'female_id' => $female_id,
            'nikah_date' => $nikah->nikah_date,
            'current_status' => 'Nikah Applied',
        ];
        $save_history = NikahDetailHistory::create($save_nikah_history);
        return $save_history;
    }

    public static function getDuplicateSlotsCount($slot_array)
    {
        $arrLength = count($slot_array);
        $duplicate_slots_array = [];
        for ($i = 0; $i < $arrLength; $i++) {
            $key = $slot_array[$i];
            if (isset($duplicate_slots_array[$key]) && $duplicate_slots_array[$key] > 0) {
                $duplicate_slots_array[$key]++;
            } else {
                $duplicate_slots_array[$key] = 1;
            }
        }
        return $duplicate_slots_array;
    }

    public static function slotsAfterRemovingBookedSlots($start_slots_array, $end_slot_array, $booked_slots_count_array)
    {
        $slots_after_removing_booked_slots = [];
        foreach ($start_slots_array as $key => $value) {
            if (isset($booked_slots_count_array['start_time'][$key])) {
                if ($booked_slots_count_array['start_time'][$key] < $value) {
                    $slots_after_removing_booked_slots['start_time'][] = $key;
                }
            } else {
                $slots_after_removing_booked_slots['start_time'][] = $key;
            }
        }


        foreach ($end_slot_array as $key => $value) {
            if (isset($booked_slots_count_array['end_time'][$key])) {
                if ($booked_slots_count_array['end_time'][$key] < $value) {
                    $slots_after_removing_booked_slots['end_time'][] = $key;
                }
            } else {
                $slots_after_removing_booked_slots['end_time'][] = $key;
            }
        }
//        dd($booked_slots_count_array['start_time'],$slots_after_removing_booked_slots);

        return $slots_after_removing_booked_slots;
    }

    public function test()
    {
        $array = [2, 5, 6, 9, 11];
        $array_length = count($array);
        $result = [];
        for ($i = 2; $i <= $array_length; $i++) {
            if (!isset($array[$i])) {
                $result[] = $i;
            }
        }
        return $result;
    }

}
