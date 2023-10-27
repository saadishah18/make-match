<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function chatUsers(){
        return $this->belongsToMany(User::class,'chat_users','chat_id','user_id');
    }

    public function messages(){
        return $this->hasMany(Message::class,'chat_id');
    }
}
