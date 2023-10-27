<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceObtained extends Model
{
    protected $guarded = ['id'];
    use HasFactory;

    public function service(){
        return $this->belongsTo(Services::class,'service_id');
    }
}
