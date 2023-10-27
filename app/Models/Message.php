<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['receiver_id', 'sender_id', 'message', 'file_id','chat_id'];

    public function chat(){
        return $this->belongsTo(Chat::class,'chat_id');
    }

    public function file(){
        return $this->belongsTo(MessageFile::class,'file_id');
    }

    public function senderUser(){
        return $this->belongsTo(User::class,'sender_id');
    }
}
