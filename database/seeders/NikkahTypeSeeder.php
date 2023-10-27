<?php

namespace Database\Seeders;

use App\Models\NikahType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NikkahTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NikahType::create([
            'name' => 'Standard Nikah',
            'description' => 'Book your Nikah session at any available date after one week.',
            'price' => 99.00,
        ]);

        NikahType::create([
            'name' => 'Express Nikah',
            'description' => 'Fast track Nikah. Select available time slots within after 48 hours.',
            'price' => 119.00
        ]);
    }
}
