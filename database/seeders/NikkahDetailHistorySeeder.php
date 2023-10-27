<?php

namespace Database\Seeders;

use App\Models\NikahDetailHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NikkahDetailHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NikahDetailHistory::create([
            'nikah_id' => 1,
            'male_id' => 3,
            'female_id' => 4,
            'nikah_date' => '2025-12-25',
        ]);
    }
}
