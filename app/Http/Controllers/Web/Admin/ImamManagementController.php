<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nikah;
use App\Models\NikahTimeTable;
use App\Models\User;
use App\Repositories\Interfaces\ImamManagementInterface;
use App\Service\Facades\Api;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ImamManagementController extends Controller
{
    protected $imam_interface;

    public function __construct(ImamManagementInterface $imamManagement)
    {
        $this->imam_interface = $imamManagement;
    }

    public function getImams(Request $request)
    {
        return Inertia::render('admin/imams/Imams', [
            'imams' => function () use ($request) {
                return $this->imam_interface->getImams();
            },
        ]);
    }

    public function changeImamStatus(Request $request)
    {
        try {
           $response = $this->imam_interface->changeImamStatus($request);
           $message = $request->status == true ? 'Imam activated successfully' : 'Imam de-activated successfully';
           return Api::response([],$message);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage().'-'.$exception->getFile().'-'.$exception->getLine());
            abort('401');
        }
    }

    public function delete(Request $request){
        try {
            $date = Carbon::now()->toDateString();
            $check_nikah_existance = Nikah::where('imam_id',$request->imam_id)->where('nikah_date','>=',$date)->exists();
            if($check_nikah_existance){
                return Redirect::back()->with('error','Imam assigned to a future nikah. Can not be deleted');
            }
            $user = User::find($request->imam_id);
            NikahTimeTable::where('imam_id',$user->id)->forceDelete();
            $user->forceDelete();
            return Redirect::back()->with('success','Imam Deleted Successfully');
        }catch (\Exception $exception){
            Log::error($exception->getMessage().'-'.$exception->getFile().'-'.$exception->getLine());
            abort('401');
        }
    }
}
