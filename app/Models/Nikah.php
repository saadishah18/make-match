<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Nikah extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $with = ['type'];

    public function history(){
        return $this->hasOne(NikahDetailHistory::class,'nikah_id');
    }

    public function services(){
        return $this->hasMany(ServiceObtained::class,'nikah_id');
    }

    public function type(){
        return $this->belongsTo(NikahType::class,'nikah_type_id');
    }

    public function paymentDetail(){
        return $this->belongsTo(Payments::class,'activity_id','id');
    }

    public function wali(){
        return $this->hasOne(Walli::class,'nikah_id');
    }

    public function witnesses(){
        return $this->hasMany(Witness::class,'nikah_id');
    }

    public function user(){
      return $this->belongsTo(User::class);
    }

    public function partner(){
      return $this->belongsTo(User::class,'partner_id','id');
    }

    public function currentUserAsWitness(){

       return $this->hasOne(Witness::class,'nikah_id','id')->where('user_as_witness_id',Auth::user()->id);
    }

    public function currentUserAsWali(){
        return $this->hasOne(Walli::class,'nikah_id','id')->where('user_as_wali_id',Auth::user()->id);
    }

    public function assignedImam(){
        return $this->belongsTo(User::class,'imam_id');
    }

    public function nikahCertificate(){
        return $this->belongsTo(Certificate::class,'activity_id')->where('activity_model','App/Nikah');
    }

    public function talaqs(){
        return $this->hasOne(Talaq::class,'nikah_id');
    }

    public function khulu()
    {
        return $this->hasOne(Khulu::class,'nikah_id');
    }
}
