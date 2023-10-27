<?php

namespace Database\Seeders;

use App\Models\PartnerDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartnerDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PartnerDetail::create([
            'male_id' => 3,
            'female_id' => 4,
            'nikah_id' => null,
        ]);
    }
}
