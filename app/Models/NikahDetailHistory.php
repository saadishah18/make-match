<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NikahDetailHistory extends Model
{
    use HasFactory;
    protected $table = 'nikah_detail_histories';

    protected $guarded  = ['id'];

    public function nikahDetail()
    {
        return $this->belongsTo(Nikah::class,'nikah_id');
    }

    public function groom(){
        return $this->belongsTo(User::class,'male_id');
    }

    public function bride(){
        return $this->belongsTo(User::class,'female_id');
    }
}
