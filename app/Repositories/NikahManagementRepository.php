<?php

namespace App\Repositories;

use App\Http\Resources\NikahResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WitnessResource;
use App\Models\Nikah;
use App\Models\User;
use App\Models\Witness;
use App\Repositories\Interfaces\NikahManagementInterface;
use App\Service\Facades\Api;
use App\Service\ImamService;
use App\Service\NikahRelatedService;
use Illuminate\Support\Facades\Log;

class NikahManagementRepository implements NikahManagementInterface
{
    public function nikahListing($request)
    {
//       $nikahs =  Nikah::paginate($request->per_page ?? 5);
       $nikahs =  Nikah::all();
      /*  $perPage = $request->query('perPage', 5);
        $page = $request->query('page', 1);

        $query = Nikah::query();
        $total = $query->count();

        $nikahs = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        return ['nikahs' => $nikahs, 'total' => $total];*/
       return $nikahs;
    }

    public function calenderNikahs($request)
    {
        return Nikah::all();
    }

    public function nikahDetail($id){
        $nikah = Nikah::find($id);
        return new NikahResource($nikah);
    }

    public function getImams($request){
        $check_imam_assigned = Nikah::find($request->nikah_id);
        if($request->type == 'change-imam'){
            $imams = ImamService::ActiveImams()->where('id','!=',$request->imam_id);
        }else{
            if($check_imam_assigned->imam_id != null){
                return errorMessage('Imam already assigned');
            }
            $imams = ImamService::ActiveImams();
        }
        $nikah_date = $request->nikah_date;
        $start_time = $request->start_time;
        $result = [];
        $nikah_detail = $check_imam_assigned;
        foreach ($imams as $key => $imam){
          $response =  NikahRelatedService::getAvailableImamOnDateAndTime($imam, $nikah_date, $start_time,$nikah_detail);
          if($response){
              $response->nikah_id = $request->nikah_id;
              $result[] = $response;
          }
        }
//        Log::info('===================');
//        Log::info(['result' => $result]);
        return $result;
    }

    public function assignImam($request){
        $check_nikah_assigned = Nikah::find($request->nikah_id);
//        if($check_nikah_assigned->imam_id != null){
//            return errorMessage('Imam already assigned');
//        }
        $update_record = Nikah::where('id',$request->nikah_id)->update(['imam_id'=> $request->imam_id]);
        return successResponse($update_record,'Imam Assigned successfully');
    }

    public function getWitnessToAssign($request){
        $check_witness = Witness::where('nikah_id',$request->nikah_id)->count();
        $result_array = [];
        if($check_witness == 2){
            $result_array['message'] = 'Witness already assigned to this nikah';
            return $result_array;
        }

        $admin_ids = User::whereHas('roles',function ($q){
           $q->where('name','admin');
        })->get()->pluck('id');

        $witnesses = User::where('active_role','witness')->whereIn('created_by',$admin_ids)->get();

        $result_array['witness'] = UserResource::collection($witnesses);
        $result_array['message'] = 'Witness Listing';
        return $result_array;
    }

    public function assignWitnessToNikah($request){
        $witnesses = $request->witness_ids;
        $created_record =[];
        foreach ($witnesses as $witness){
            $create_data = [
                'nikah_id' => $request->nikah_id,
                'user_as_witness_id' => $witness,
                'invited_by' => auth()->id(),
                'is_invitation_accepted' => 1
            ];
            $created_record[] = Witness::create($create_data);
        }
        return $created_record;
    }
}
