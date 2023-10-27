<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NikahTimeTable extends Model
{
    use HasFactory;

    protected $table = 'nikah_time_table';

   /* protected $fillable = [
        'title','imam_id','on_days','off_days'.'on_dates','off_dates'
    ];*/

    protected $guarded = ['id'];

    protected function ondays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }

    protected function offdays(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }

    protected function ondates(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }

    protected function offdates(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }

    public function imamDetail(){
        return $this->belongsTo(User::class,'imam_id');
    }

    public static function getSlotsArrayOnDate($nikah_date, $imam_id = null)
    {
        $previous_date = Carbon::parse($nikah_date)->subDay()->toDateString();
        $next_date = Carbon::parse($nikah_date)->addDay()->toDateString();

        $where_dates = [$previous_date, $nikah_date, $next_date];
        $get_slots_from_time_table = [];
        foreach ($where_dates as $date){
            $date = '"' . $date . '"';
            $get_slots_from_time_table[] = NikahTimeTable::selectRaw("JSON_EXTRACT(defined_slots, '$.$date') as result,imam_id")
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(defined_slots, '$.$date')) IS NOT NULL")->whereRaw("deleted_at IS NULL")
                ->when($imam_id, function ($q) use ($imam_id){
                    $q->where('imam_id', $imam_id);
                })->get();
        }
        $total_slots = [];
        foreach ($get_slots_from_time_table as $key => $slots_data) {
            if (!$slots_data->isEmpty()) {
                foreach ($slots_data as $i => $slot_data){
                    $slots = json_decode($slot_data->result);
                    foreach ($slots as $index => $slot_detail) {
                        if (property_exists($slot_detail, "start_time")) {
                            $startTime = $where_dates[$key] . ' ' . $slot_detail->start_time;
                            $carbonStartTime = Carbon::parse($startTime);
                            $total_slots['start_time'][] = $carbonStartTime->toDateTimeString();
                        }
                        if (property_exists($slot_detail, "end_time")) {
                            $endTime = $where_dates[$key] . ' ' . $slot_detail->end_time;
                            $carbonEndTime = Carbon::parse($endTime);
                            $total_slots['end_time'][] = $carbonEndTime->toDateTimeString();
                        }
                    }
                }

            }
        }
        return $total_slots;
    }

}
