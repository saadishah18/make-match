<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function malePartnerUser(){
        return $this->belongsTo(User::class,'male_id');
    }

    public function femalePartnerUser(){
        return $this->belongsTo(User::class,'female_id');
    }

    public function nikah(){
        return $this->belongsTo(Nikah::class,'nikah_id');
    }

    public function requestedByPersonData(){
        return $this->belongsTo(User::class,'requested_by');
    }

    public function userAsPartnerData(){
        return $this->belongsTo(User::class,'requested_to_be_partner');

    }
}
