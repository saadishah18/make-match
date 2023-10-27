<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruju extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function nikah(){
        return $this->belongsTo(Nikah::class,'nikah_id');
    }
}
