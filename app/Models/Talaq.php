<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talaq extends Model
{
    use HasFactory;

    protected $fillable = [
        'male_id', 'partner_id', 'nikah_id', '1st_talaq_date', '2nd_talaq_date',
        '3rd_talaq_date', 'talaq_counter', 'is_confirmed_by_otp',
        'is_ruju_applied'
    ];

    protected $dates= ['1st_talaq_date','2nd_talaq_date','3rd_talaq_date'];

    public function nikah(){
        return $this->belongsTo(Nikah::class,'nikah_id');
    }

    public function pregnancyDetail(){
        return $this->hasOne(PregnancyDetail::class,'talaq_id');
    }
}
