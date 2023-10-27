<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{

    public function toArray($request)
    {
//        $sender = User::find($this->sender_id);
//        $receiver = User::find($this->receiver_id);
//        $files = $this->file ? $this->file->filename : [];
//        foreach ($files as &$file) {
//            $file->path = public_path('/files/messages/' . $file->path);
//        }
        $message_time = '';
        if($this->created_at->format('Y-m-d') === Carbon::now()->format('Y-m-d')){
            $message_time = $this->created_at->diffForHumans();
        }
//        dd($this->senderUser);
        return [
            'id' => $this->id,
            'receiver_id' => $this->receiver_id,
            'chat_id' => $this->chat->id,
            'sender_id' => $this->sender_id,
            'message' => $this->message,
            'time' => $message_time != '' ?  $message_time : $this->created_at->format('d/M/Y'),
            'message_time' => $this->created_at,
            'file_url' => $this->file ? url('files/messages').'/'.$this->file->filename : '',
            'file_name' => $this->file ? $this->file->filename : '',
            'file_type' => $this->file ? $this->file->filetype : '',
            'user_image' => $this->senderUser ? imagePath($this->senderUser->profile_image,'profile_image') : "",
            'role' => isset($this->role) ? $this->role: '',
        ];
    }
}
