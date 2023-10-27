<?php

namespace App\Http\Controllers\Api;

use App\Events\StripeWebhookEvent;
use App\Http\Controllers\Controller;

use App\Repositories\Interfaces\NikahInterface;
use Illuminate\Http\Request;
use App\Service\Facades\Api;
use App\Http\Requests\NikahRequest;

class NikahController extends Controller
{
    protected $nikah_interface;
    public function __construct(NikahInterface $nikahInterface)
    {
        $this->nikah_interface = $nikahInterface;
    }

    public function calendarDates(Request $request){
        try {
            $result = $this->nikah_interface->calendarDates($request);
            if($result['error'] == false){
                return Api::response($result['data'], $result['message'],$result['status']);
            }else{
                return Api::error( $result['message'],$result['status']);
            }
        }catch (\Exception $exception){
//            dd($exception->getMessage(),$exception->getLine(), $exception->getFile(), $exception->getTrace());
            return Api::server_error($exception);
        }
    }

    public function getDateSlots(Request $request){
        try {
            $result = $this->nikah_interface->getDateSlots($request);
            if($result['error'] == false){
                return Api::response($result['data'],$result['message'],$result['status']);
            }else{
                return Api::error($result['message'],$result['status']);
            }
        }catch (\Exception $exception){
//            dd($exception->getMessage(),$exception->getLine(), $exception->getFile(), $exception->getTrace());
            return Api::server_error($exception);
        }
    }

    public function saveNikahAsDraft(NikahRequest $request): \Illuminate\Http\JsonResponse
    {


        try {
        /*    $data = $request->all();
            if (in_array('nikah_with_wali', $data['services'])) {
                $user = User::where('email',$data['wali_email'])->exists();
               if($user){
                   return Api::error('Wali email '.$data['wali_email'].' already exits');
               }
            }

            if (in_array('own_witness', $data['services'])) {
                foreach ($data['witness_email'] as $key => $email){
                    $user = User::where('email',$email)->exists();
                    if($user){
                        return Api::error('Witness email '.$email.' already exits');
                    }
                }
            }*/

           $result = $this->nikah_interface->saveNikahAsDraft($request);
//           dd($result);
            if(isset($result['status']) && $result['status'] == 422){
                return Api::error($result['message']);
            }else{
                $response['payment_intent'] = $result;
                $response['client_secret'] = config('services.stripe_testing.secret');
                return Api::response($response);
            }

            /*if(isset($response['error']) && $response['error'] == false){
                return Api::response($response['data'],$response['message'],$response['status']);
            }else{
                dd('here');
                return Api::error($response['message'],$response['status']);
            }*/
        }catch (\Exception $exception){
//            DB::rollback();
//            dd($exception->getMessage(),$exception->getLine(),$exception->getFile());
            return Api::server_error($exception);        }
    }


/*    public function saveNikKah(NikahRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->nikah_interface->saveNikKah($request);
            return Api::response($response);
            if(isset($response['error']) && $response['error'] == false){
                return Api::response($response['data'],$response['message'],$response['status']);
            }else{
                dd('here');
                return Api::error($response['message'],$response['status']);
            }
        }catch (\Exception $exception){
            dd($exception->getMessage(),$exception->getLine(),$exception->getFile(),$exception->getTrace());
        }
    }*/

    public function webHook(Request $request)
    {

        $complete_object = $request->all();
//        dispatch(new StripeWebhookEvent($complete_object));
//        event(new StripeWebhookEvent($complete_object));
        StripeWebhookEvent::dispatch($complete_object);

        return response('success', 200);

    }

    public function resendInvitation(Request $request)
    {
        try {
            if (!Api::validate([
                'nikah_id' => 'required',
                'email' => 'required',
                'old_user' => 'required',

            ])) {
                return Api::validation_errors();
            }
            return $this->nikah_interface->resendInvitation($request);

        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
