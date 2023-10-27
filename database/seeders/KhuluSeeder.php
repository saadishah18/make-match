<?php

namespace Database\Seeders;

use App\Models\Khulu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KhuluSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Khulu::create([
           'male_id' => 3,
           'partner_id' => 4,
           'nikah_id' => 1,
           'otp_verified' => 1,
           'is_declined' => 0,
           'is_accepted' => 1,
           '1st_khulu_applied_date' => '2023-05-01',
           'khulu_counter' => 1,
           'reason' => 'Not upto my mark',
           'details' => 'Not upto my markNot upto my markNot upto my markNot upto my markNot upto my markNot upto my markNot upto my markNot upto my markNot upto my mark',
        ]);
    }
}
