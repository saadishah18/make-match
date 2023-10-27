<?php

namespace Database\Seeders;

use App\Models\NikahTimeTable;
use App\Models\TimeTableSlot;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeTableSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('time_table_slots')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $get_dates_details = NikahTimeTable::all();
        $time_slot_minutes = config('app.time_slot_minutes');
        $shift_start_time = config('app.shift_start_time');
        $shift_end_time = config('app.shift_end_time');

        $total_no_of_slots_in_an_hour = 60 / $time_slot_minutes;

        $total_no_of_working_hours_per_day = date('H', strtotime($shift_end_time)) - date('H', strtotime($shift_start_time));

        $six_month_date = Carbon::now()->daysUntil(Carbon::now()->addMonths(5));
        $on_dates = [];
        $off_dates = [];
        $on_days = [];
        $off_days = [];
        foreach ($get_dates_details as $ky => $time_table_date) {
            $on_dates = $time_table_date->on_dates['on_dates'];
            $off_dates = $time_table_date->off_dates['off_dates'];
            $on_days = $time_table_date->on_days['on_days'];
            $off_days = $time_table_date->off_days['off_days'];

        }
        $save_slots = [];

        foreach ($six_month_date as $key => $date) {
            $date_stirng = $date->toDateString();
            $day_sting = $date->dayName;
            if (!in_array($date_stirng, $off_dates)){
               if(!in_array($day_sting, $off_days)){
                   $slot_start_time = Carbon::parse($date_stirng . $shift_start_time);
                   $slot_end_time = Carbon::parse($date_stirng . $shift_start_time)->addMinutes($time_slot_minutes);
                   for ($i = 1; $i <= $total_no_of_working_hours_per_day; $i++) {
                       for ($j = 0; $j < $total_no_of_slots_in_an_hour; $j++) {
                           if ($j > 0) {
                               $slot_start_time = $slot_start_time->addMinutes($time_slot_minutes);
                               $slot_end_time = $slot_end_time->addMinutes($time_slot_minutes);
                           }
                           $save_slots[] = [
                               'imam_id' => $time_table_date->imam_id,
                               'date' => $date_stirng,
                               'start_time' => $slot_start_time->toTimeString(),
                               'end_time' => $slot_end_time->toTimeString()
                           ];
                       }
                   }
               }
            }
        }
        $create_slots = TimeTableSlot::insert($save_slots);

    }
}
