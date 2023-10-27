<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khulu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nikah(){
        return $this->belongsTo(Nikah::class,'nikah_id');
    }

    public function khuluImam(){
        return $this->belongsTo(User::class,'imam_id');
    }

    public function male(){
       return $this->belongsTo(User::class,'male_id');
    }

    public function female(){
       return $this->belongsTo(User::class,'partner_id');
    }
}
