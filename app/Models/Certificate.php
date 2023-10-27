<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    use ImageTrait;

    protected $guarded = ['id'];

    public function nikah(){
        return $this->belongsTo(Nikah::class,'activity_id');
    }

    public function khula(){
        return $this->hasOneThrough(Khulu::class,Nikah::class,'khulu_id','nikah_id','id','id');
    }
}
