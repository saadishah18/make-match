<?php

namespace Database\Seeders;

use App\Models\Talaq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TalaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Talaq::create([
            'male_id' => 3,
            'partner_id' => 4,
            'nikah_id' => 1,
            '1st_talaq_date' => '2022-12-31',
            'talaq_counter' => 1,
            'is_confirmed_by_otp' => 1,
            'is_ruju_applied' => 1,
        ]);
    }
}
