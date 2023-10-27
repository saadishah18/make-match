<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTableSlot extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

//    protected $dates = ['start_time','end_time'];

    public function imamDetail(){
        return $this->belongsTo(User::class,'imam_id');
    }
    public function nikahTimetable(){
        return $this->belongsTo(NikahTimeTable::class,'imam_id','imam_id');
    }
}
