<?php

namespace App\Http\Controllers;

use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahType;
use App\Models\PartnerDetail;
use App\Models\ServiceObtained;
use App\Models\Services;
use App\Models\User;
use App\Service\NikahRelatedService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends Controller
{

    public function makePartner()
    {
        $user = User::find(8);
        $user_partner = User::find(9);
        $data = [
            'male_id' => $user->id,
            'female_id' => $user_partner->id,
        ];

        $make_partner = PartnerDetail::create($data);
        echo "done";
    }

    public function testNikah()
    {
        $nikah_type = NikahType::find(1);
//        $nikah_services = Services::whereIn([1, 2, 3])->get();
        $id = 8;
        $user = User::find($id);
        if ($user->gender == 'male') {
            $partner = $user->femalePartners->first();
        } else {
            $partner = $user->malePartner;
        }
        $data = [
            'nikah_type_id' => 1,
            'user_id' => $user->id,
            'partner_id' => $partner->id,
            'nikah_date' => date('Y-m-d', strtotime(now())),
            'nikah_date_time' => Carbon::now()->toDateTime(),

        ];
        $nikah_created = Nikah::create($data);
        if ($user->gender == 'Male') {
            $male_id = $user->id;
            $female_id = $partner->id;
        }

        if ($user->gender == 'Female') {
            $female_id = $user->id;
            $male_id = $partner->id;
        }


        $nikah_detail_history = [
            'nikah_id' => $nikah_created->id,
            'male_id' => $male_id,
            'female_id' => $female_id,
            'nikah_date' => date('Y-m-d', strtotime(now())),
        ];

        $detail_created = NikahDetailHistory::create($nikah_detail_history);
        $update_partner = PartnerDetail::where('male_id',$male_id)->where('female_id',$female_id)->update(['nikah_id' => $nikah_created->id]);
        echo "done";
    }

    public function services_obtained_in_nikah(){
        $services = Services::whereIN('id',[1,3,8,4])->get();
        foreach ($services as $service){
            $add_services_obtain_in_nikah = [
                'nikah_id'=> 7,
                'service_id' => $service->id,
            ];
            $create_service_detail = ServiceObtained::create($add_services_obtain_in_nikah);
            echo "done";
        }
    }


    public function saveDates(){
       $dates =  NikahRelatedService::onOfDates();
    }
    public function saveAvaliableDates(){
       $dates =  NikahRelatedService::saveAvaliableDates();
    }
    public function makeSlots(){
       $dates =  NikahRelatedService::makeSlots();
    }
}
