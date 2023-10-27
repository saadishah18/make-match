<?php

namespace App\Repositories;

use App\Events\MessageEvent;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageFile;
use App\Models\User;
use App\Service\Facades\Api;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;

class ChatRepository
{
    public function adminMessages($request)
    {
        if(isset($request['chat_id']) && $request['chat_id'] != null){
            $chats = Chat::where('id',$request['chat_id'])->orderBy('created_at','desc')->get();
        }else{
            $chats = Chat::orderBy('created_at','desc')->get();
        }

        $messages = [];
        foreach ($chats as $chat){
            $name = explode('-',$chat->name);
            $message_time = '';
            if($chat->messages->last() != null && $chat->messages->last()->created_at->format('Y-m-d') === Carbon::now()->format('Y-m-d')){
                $message_time = $chat->messages->last() != null ? $chat->messages->last()->created_at->diffForHumans() : '';
            }else{
                $message_time = $chat->messages->last() != null? $chat->messages->last()->created_at->format('Y/M/d') : '';
            }
            $user = User::find($name[1]);
//            dd($user);
            $messages[] = [
                'chat_id' => $chat->id,
//                'chat_name' => str_replace('-','', $name[0]),
                'chat_name' => $user ? fullName($user->first_name, $user->last_name) :'',
                'profile_image' => $user ? imagePath($user->profile_image,'profile_image') : null,
                'time' => $message_time,
                'message' =>$chat->messages->last() != null? $chat->messages->last()->message != null ? $chat->messages->last()->message : 'file' : '',
                'is_highlight' => false,
            ];
        }
        return $messages;
    }

    public function chatDetail($chat_id)
    {
      /*  $receiver_id = auth()->id();
        $messages = Message::where(function($query) use ($sender_id, $receiver_id) {
          $query->where('receiver_id', $receiver_id)->where('sender_id',$sender_id);
      })->orWhere(function ($query) use ($sender_id, $receiver_id) {
          $query->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
      })->orderBy('created_at', 'ASC')->get();*/

        $messages = Message::where('chat_id',$chat_id)->orderBy('created_at','asc')->get();
        return $messages;
    }

    public function postReply($request)
    {
        $data = [
            'receiver_id' => $request['receiver_id'],
            'sender_id' => $request['sender_id'],
            'message' => $request['message'],
        ];
        if ($request->file('file')) {
            $path = 'files/messages/';
            $file = $request->file('file');
            $name = generate_filename($file);

            $save_file = [
                'filename' => $name,
                'filetype' => $file->getClientOriginalExtension(),
                'size' => filesize($file),
            ];
            $file->move($path, $name);
            $create_image = MessageFile::create($save_file);
//            dd($name);


            $data['file_id'] = $create_image->id;
        }
        $user = User::find($request['receiver_id']);

        $chat = Chat::find($request['chat_id']);

        if(!$chat){
            $chat = Chat::create(['id' => $request['chat_id'], 'name' => fullName($user->first_name, $user->last_name).'-'.$user->id, 'type' => 'single']);
        }
        $data['chat_id'] = $chat->id;

        $chat->chatUsers()->attach([$data['receiver_id']]);

        $message = Message::create($data);
        $receiver = getReceiver(chatUsers($request['chat_id']));
//        $modifiedCollection = $message->map(function ($item) use($receiver) {
//            $item['role'] = $receiver->roles->first()->name;
//            return $item;
//        });

        $broadcast = broadcast(new MessageEvent($message,$receiver->id));

        return Api::response(new MessageResource($message));
    }

    public function getAllUsersForChat($request = null){
        $users = User::with('chatUsers')->wherehas('roles',function ($q){
            $q->where('name','user');
        })->doesnthave('chatUsers')->get();

        $return_array = [];
        foreach ($users as $user){
            if($user->first_name != null){
                $return_array[] = [
                    'id' => $user->id,
                    'value' => $user->id,
                    'name' => fullName($user->first_name,$user->last_name).' ('.$user->email.')',
                    'chat_id' => getChatNewId($user),
                    'profile_image' => $user->profile_image ? imagePath($user->profile_image,'profile_image') : '/assets/images/users/avatar1.jpg'
                ];
            }

        }
        return Api::response($return_array,'User List');
    }
}
