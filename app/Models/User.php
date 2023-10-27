<?php

namespace App\Models;

use App\Http\Resources\WaliResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    protected $with = ['roles'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'phone_verified_at',
        'address',
        'gender',
        'profile_image',
        'id_card_number',
        'id_expiry',
        'date_of_birth',
        'id_card_front',
        'id_card_back',
        'selfie',
        'qr_number',
        'active_role',
        'is_active',
        'is_accept',
        'created_by',
        'country_code',
        'country_name',
        'timezone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function generateQRNumber()
    {
        do {
            $code = generate_code(8);
//        } while (self::where('qr_number', $code)->exists());
        } while (static::where('qr_number', $code)->exists());
        if(env('APP_ENV') != 'local'){
            generate_qr_image($code);
        }
        return $code;
    }

/*    public function partner(){
        if($this->gender == 'male'){
            return $this->femalePartners();
        }elseif($this->gender == 'female'){
            return $this->malePartner();
        }
    }*/

    public function partnerDetail(){
        return $this->hasMany(PartnerDetail::class, 'requested_to_be_partner');
    }


    public function femalePartners(){
        return $this->hasMany(PartnerDetail::class,'male_id','id');
    }

    public function malePartner(){
        return $this->hasOne(PartnerDetail::class,'female_id','id');
    }

    public function malePaymentTransaction(){
        return $this->hasMany(Payments::class,'male_id','id');
    }

    public function femalePaymentTransaction(){
        return $this->hasMany(Payments::class,'female_id','id');
    }

    public function nikahs(){
        return $this->hasMany(Nikah::class,'user_id');
    }

    public function imamNikahs(){
        return $this->hasMany(Nikah::class,'imam_id');
    }

    public function maleCertificates(){
        return $this->hasMany(Certificate::class,'male_id');

    }

    public function femaleCertificates(){
        return $this->hasMany(Certificate::class,'female_id');
    }

    public function chatUsers(){
        return $this->belongsToMany(Chat::class,'chat_users','user_id','chat_id');
    }


}
