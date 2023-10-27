<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use PHPUnit\TextUI\Exception;

class ChatController extends Controller
{
    protected $chat_repository;

    public function __construct(ChatRepository $repository)
    {
        $this->chat_repository = $repository;
    }

    public function index(Request $request){
        try {
            return Inertia::render('admin/chat/Chat', [
                'chats' => function () use ($request) {
                    return $this->chat_repository->adminMessages($request);
                },
            ]);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }

    public function chatDetail($sender_id){
        try {
           $messages =  $this->chat_repository->chatDetail($sender_id);
           if(count($messages)){
               return Api::response(MessageResource::collection($messages));

           }else{
               return Api::response([],'Please Initiate chat');
           }

        }catch (\Exception $exception){

        }
    }

    public function postAdminReply(Request $request){
        try {
//            $request->validate([
//                'message' => 'required_without_all:file',
//                'file' => 'required_without_all:message|file|mimes:jpeg,jpg,png,gif,mp4,avi,wmv,mov|max:20480',
//                ]);
            return $this->chat_repository->postReply($request);
        }catch (\Exception $exception){
            return Api::server_error($exception);        }
    }

    public function getAllUsersForChat(){
        try {
            return $this->chat_repository->getAllUsersForChat();
        }catch (\Exception $exception){
            return Api::server_error($exception);        }
    }

    public function getLatestChats(Request $request){
        try {
            $chats = $this->chat_repository->adminMessages($request);
            return Api::response($chats);
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
