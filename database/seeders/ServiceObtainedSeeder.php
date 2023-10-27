<?php

namespace Database\Seeders;

use App\Models\ServiceObtained;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceObtainedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceObtained::create([
            'nikah_id' => 1,
            'service_id' => 1,
        ]);
        ServiceObtained::create([
            'nikah_id' => 1,
            'service_id' => 2,
        ]);
        ServiceObtained::create([
            'nikah_id' => 1,
            'service_id' => 3,
        ]);
        ServiceObtained::create([
            'nikah_id' => 1,
            'service_id' => 4,
        ]);
    }
}
