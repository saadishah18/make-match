<?php

namespace Database\Seeders;

use App\Models\PortalSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PortalSetting::create([
            'name' => 'vat',
            'value' => 10,
        ]);

        PortalSetting::create([
            'name' => 'khulu_fees',
            'value' => 80
        ]);
    }
}
