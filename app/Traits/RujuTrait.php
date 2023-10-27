<?php

namespace App\Traits;

use App\Models\Ruju;
use Illuminate\Support\Facades\DB;

trait RujuTrait
{

    public function checkRujuExits($user)
    {
        $check_ruju_exits = Ruju::where('male_id', $user->id)->orWhere('partner_id', $user->id)->exists();
        return $check_ruju_exits;
    }

    public function canApplyRuju($user ,$talaq)
    {
       $flag = true;
        $ruju = Ruju::where(function ($query) use ($user) {
            $query->where('male_id', $user->id)->orWhere('partner_id', $user->id);
        })/*->where('ruju_counter', '>=', 2)
        ->when(false, function ($query) {
            $query->whereIN('first_ruju_status', ['rejected', 'completed']);
        })
        ->when('ruju_counter' == 2 && '2nd_ruju_applied_date' != null, function ($query) {
                $query->whereIN('second_ruju_status', ['rejected', 'completed']);
        })*/
        ->first();
        if($ruju){
            if($ruju->first_ruju_status == 'complete'  && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 1 ){
                $flag = false;
            }
           /* elseif ($ruju->first_ruju_status == 'complete' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 2){
                $flag = false;
            }
           /* elseif ($ruju->first_ruju_status == 'complete' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 2){
                $flag = false;
            }*/
            elseif ($ruju->second_ruju_status == 'complete' && $ruju->ruju_counter == 2 && $talaq->talaq_counter == 2){
                $flag = false;
            }
            // If two talaqs applied consecutively but ruju applied only once.
            elseif ($ruju->second_ruju_status == 'complete' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 2){
                $flag = false;
            }elseif ($ruju->first_ruju_status == 'requested' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 1){
                $flag = false;
            }elseif ($ruju->second_ruju_status == 'requested' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 1){
                $flag = false;
            }elseif ($ruju->second_ruju_status == 'requested' && $ruju->ruju_counter == 1 && $talaq->talaq_counter == 2){
                $flag = false;
            }elseif ($ruju->second_ruju_status == 'requested' && $ruju->ruju_counter == 2 && $talaq->talaq_counter == 2){
                $flag = false;
            }
        }

        return $flag;
    }
}
