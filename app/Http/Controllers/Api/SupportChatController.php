<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use App\Service\Facades\Api;

class SupportChatController extends Controller
{
    public function index(MessageRepository $chat_repository): \Illuminate\Http\JsonResponse
    {
        try {
            return $chat_repository->get_one(auth()->id());
        } catch (\Exception $exception) {
            return Api::server_error($exception);
        }
    }

    public function store(MessageRepository $chat_repository): \Illuminate\Http\JsonResponse
    {
        try {
            return $chat_repository->add();
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getCode(),$exception->getFile(),$exception->getLine());
            return Api::server_error($exception);
        }
    }
}
