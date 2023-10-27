<?php

namespace Database\Seeders;

use App\Models\Nikah;
use App\Models\PartnerDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NikahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $nikah =  Nikah::create([
            'nikah_type_id' => 1,
            'user_id' => 3,
            'partner_id' => 4,
            'nikah_date' => '2022-12-25',
            'nikah_date_time' => '2022-12-25 19:55:23',
        ]);

       $partner_detail = PartnerDetail::where('male_id',3)->where('female_id',4)->update(['nikah_id' => $nikah->id]);
    }
}
