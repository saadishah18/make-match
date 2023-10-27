<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\ImamManagementInterface;
use App\Service\ImamService;

class ImamManagementRepository implements ImamManagementInterface
{
    public function getImams(){
      return ImamService::Imams();
    }

    public function changeImamStatus($request){
        $user_id = $request->imam_id;
        $status = $request->status == true ? 1 : 0;
        $user = User::where('id',$user_id)->update(['is_active' =>$status]);
        return true;
    }
}
