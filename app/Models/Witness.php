<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Witness extends Model
{
    use HasFactory;

    protected $table = 'witness';

    protected $guarded = ['id'];

    public function user(){
       return $this->belongsTo(User::class,'user_as_witness_id');
    }
}
