<?php


namespace App\Repositories;


use App\Events\MessageEvent;
use App\Http\Resources\MessageResource;
use App\Http\Resources\PaginationResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MessageFile;
use App\Models\User;
use App\Repositories\Interfaces\Repository;
use App\Service\Facades\Api;
use App\Traits\ImageTrait;

class MessageRepository implements Repository
{
    use ImageTrait;

    public function get_all(): \Illuminate\Http\JsonResponse
    {
        return Api::response();
    }

    public function get_one($id): \Illuminate\Http\JsonResponse
    {
//        $user_ids = [auth()->id(), $id];
        $chat_detail = Chat::where('name','admins_chat-'.auth()->id())->first();
        /*$data = Message::whereIn('receiver_id', $user_ids)->whereIn('sender_id', $user_ids)
            ->orderBy('created_at','desc')
            ->paginate(request('per_page', 15));*/
        if($chat_detail){
            $data = Message::where('chat_id',$chat_detail->id)
                ->orderBy('created_at','desc')
                ->paginate(request('per_page', 15));
          if(count($data)){
              return Api::response([
                  'list' => MessageResource::collection($data),
                  'pagination' => new PaginationResource($data),
                  'chat_id' => $chat_detail->id
              ],'Message Lists');
          }else{
              return Api::response(['chat_id' => $chat_detail->id],'No message exists yet');
          }

        }else{
            $create_chat = Chat::create(['name' =>'admins_chat'.'-'.auth()->id(), 'type' => 'single']);
            return Api::response(['chat_id' => $create_chat->id],'No Chat exists yet');
        }

    }

    public function add(): \Illuminate\Http\JsonResponse
    {
        ini_set('upload_max_filesize', '20M');

        request()->validate([
            'message' => 'required_without_all:file',
            'file' => 'required_without_all:message|mimetypes:image/jpeg,image/jpg,image/png,image/gif,video/mp4,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,video/x-msvideo,video/x-ms-wmv,video/quicktime|max:10240', // 10MB
        ]);
        $data = [
            'receiver_id' => 0,
            'sender_id' => auth()->id(),
            'message' => request('message'),
            'chat_id' => request('chat_id'),
        ];



        if (request()->file('file')) {
            $image_validation = $this->image_validation(request()->file('file'));
            if ($image_validation) {
                $path = 'files/messages/';
                $file = request()->file('file');

                $name = generate_filename($file);
                $save_file = [
                    'filename' => $name,
                    'filetype' => $file->getClientOriginalExtension(),
                    'size' => filesize($file),
                ];
                $file->move($path, $name);
                $create_image = MessageFile::create($save_file);
                $data['file_id'] = $create_image->id;
            }
        }

        $receiver_ids = User::whereHas('roles',function ($q){
            $q->where('name','admin');
        })->get()->pluck('id')->toArray();

        $receiver_ids[] = $data['sender_id'];

        $user = auth()->user();

        $check_chat_exists = Chat::where('type','single')->where('name','admins_chat'.'-'.$user->id)->first();


        if($check_chat_exists){
            $data['chat_id'] = $check_chat_exists->id;

            $check_chat_exists->chatUsers()->attach($receiver_ids);
        }else{

            $create_chat = Chat::create(['name' =>'admins_chat'.'-'.$user->id, 'type' => 'single']);
            $data['chat_id'] = $create_chat->id;
            $create_chat->chatUsers()->attach($receiver_ids);

        }
        $message = Message::create($data);


        $searchIndex = array_search(auth()->id(), $receiver_ids);

        if ($searchIndex !== false) {
            unset($receiver_ids[$searchIndex]);
        }
        foreach ($receiver_ids as $receiver_id){
            broadcast(new MessageEvent($message, $receiver_id));
        }
        return Api::response(new MessageResource($message));
    }

    public function update($model): \Illuminate\Http\JsonResponse
    {
        return Api::response();
    }

    public function remove($id): \Illuminate\Http\JsonResponse
    {
        return Api::response();
    }


}
