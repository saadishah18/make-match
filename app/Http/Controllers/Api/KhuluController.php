<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Khulu;
use App\Repositories\Interfaces\KhuluInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KhuluController extends Controller
{
    protected $khuluInterface;

    /**
     * @param $khuluInterface
     */
    public function __construct(KhuluInterface $khuluInterface)
    {
        $this->khuluInterface = $khuluInterface;
    }

    public function applyKhulu(Request $request){

        $request->validate([
            'nikah_id'=>['required','exists:nikahs,id'],
            'reason'=> ['required','string','max:254'],
//            'detail'=> 'required'
        ]);
        if($request->detail == '' || $request->detail == null){
            return Api::error('Detail field is required');
        }
        try {
            $result['payment_intent'] = $this->khuluInterface->applyKhulu($request);
            $result['client_secret'] = config('app.stripe_secret_key');
            return Api::response($result, 'Khulu applied successfully');
        }catch (\Exception $ex){
            return Api::server_error($ex);
        }
    }

    public function acceptKhuluRequest(Request $request){
        try{
            $request->validate(['khulu_id' => ['required', 'exists:khulus,id']]);
            if(Str::lower(Auth::user()->gender) == 'male'){
                return $this->khuluInterface->acceptKhuluRequest($request);
            }
            return Api::response([],'Female can not accept khulu request',403);
        }catch(\Exception $ex){
            return Api::server_error($ex);
        }
    }

    public function rejectKhuluRequest(Request $request){
        try{
            $request->validate(['khulu_id' => ['required', 'exists:khulus,id']]);
            if(Str::lower(Auth::user()->gender) == 'female'){
                return $this->khuluInterface->rejectKhuluRequest($request);
            }
            return Api::response([],'Male can not discarded khulu request',403);
        }catch(\Exception $ex){
            return Api::server_error($ex);
        }
    }

}
