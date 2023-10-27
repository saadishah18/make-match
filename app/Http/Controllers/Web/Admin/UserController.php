<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\NikahResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function __construct(){

    }

    public function index(){
        try {
            return Inertia::render('admin/users/index', [
                'users' => function () {
                    return User::whereHas('roles',function ($q){
                        $q->where('name','user');
                    })->get();
                },
            ]);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }
}
