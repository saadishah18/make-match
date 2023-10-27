<?php

namespace Database\Seeders;

use App\Models\Ruju;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RujuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ruju::create([
            'male_id' => 3,
            'partner_id' => 4,
            'nikah_id' => 1,
            'talaq_id' => 1,
            'otp_verified' => 1,
            '1st_ruju_applied_date' => '2023-01-04',
            'ruju_counter' => 1
        ]);
    }
}
