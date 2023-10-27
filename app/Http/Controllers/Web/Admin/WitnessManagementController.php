<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Witness;
use App\Repositories\Interfaces\WitnessInterface;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PharIo\Version\Exception;

class WitnessManagementController extends Controller
{
    protected $witness_interface;

    public function __construct(WitnessInterface $witnessInterface)
    {
        $this->witness_interface = $witnessInterface;
    }

    public function index(Request $request){
        try {
            return Inertia::render('admin/witnesses/Witnesses', [
                'witnesses' => function () use ($request) {
                    return $this->witness_interface->getActivewitness($request);;
                },
            ]);
        }catch (\Exception $exception){
            dd($exception->getMessage());
        }
    }

    public function store(Request $request){
        try {
          $witness =  $this->witness_interface->storeWitness($request);
        /*    return Inertia::render('admin/witnesses/Witnesses',[
                'witnesses'=> $witness])->with('message','Witness Added Successfully');*/
            return Redirect::route('witness.index')->with('success','Witness Added Successfully');
        }catch (Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function deleteWitness(Request $request){
        try {
            $check_witness_assignment = Witness::where('user_as_witness_id',$request->witness_id)->exists();
            if($check_witness_assignment){
//                return response()->json(['message' => 'Witness associated with nikahs, Can not delete'], 422);
                return Redirect::back()->with('message','Witness associated with nikahs, Can not be deleted');
            }
            $user = User::find($request->witness_id);
            $user->forceDelete();
            return Redirect::back()->with('success','Witness Deleted Successfully');
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }
}
