<?php

namespace Database\Seeders;

use App\Models\NikahTimeTable;
use App\Models\User;
use App\Service\NikahRelatedService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NikahTimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('nikah_time_table')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $imams = User::whereHas('roles',function ($q){
            $q->where('id',2);
        })->get(['id','email']);

        $time_slot_minutes = config('app.time_slot_minutes');
        $shift_start_time = config('app.shift_start_time');
        $shift_end_time = config('app.shift_end_time');
        $week_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

//        $on_dates = [];
//        $off_dates = [''];

        foreach ($imams as $key => $imam){
            $rand_keys = array_rand( $week_days, rand(2,5));
            $off_days = [];
            foreach ($rand_keys as $rand_key){
                $off_days['off_days'][] = $week_days[$rand_key];
            }
            $on_days['on_days'] = array_diff($week_days, $off_days['off_days']);

            for($i=1; $i <= 7; $i++){
                $on_dates['on_dates'][] = now()->addDays(rand(0,180))->toDateString();
            }
            for($i=1; $i <= 7; $i++){
                $off_dates['off_dates'][] = now()->addDays(rand(0,180))->toDateString();
            }
            $shift_time = [];

            for ($i=0; $i < count($week_days); $i++){
                if(in_array($week_days[$i],$on_days['on_days'])){
                    $start_of_day = Carbon::parse($shift_start_time);
                    $end_of_day = Carbon::parse($shift_end_time); // 17
                    $random_value = rand(1,5);
                    $shift_time['shift_time'][$week_days[$i]]['start_time'] = $start_of_day->addHours($random_value)->format('H:i:s');
                    $shift_time['shift_time'][$week_days[$i]]['end_time'] = $start_of_day->addHours($random_value + 3)->format('H:i:s');
                }
            }

            $save_data = [
                'imam_id' => $imam['id'],
                'on_days' => json_encode($on_days),
                'off_days' => json_encode($off_days),
                'on_dates' => json_encode($on_dates),
                'off_dates' => json_encode($off_dates),
                'shift_time' => json_encode($shift_time),

            ];
            $create_time_table = NikahTimeTable::create($save_data);
        }
        NikahRelatedService::onOfDates();
        NikahRelatedService::saveAvaliableDates();
        NikahRelatedService::makeSlots();
        echo "completed";
    }
}
