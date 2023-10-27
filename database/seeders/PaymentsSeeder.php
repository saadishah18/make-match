<?php

namespace Database\Seeders;

use App\Models\Payments;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Payments::create([
            'male_id' => 3,
            'female_id' => 4,
            'activity_id' => 1,
            'activity_name' => 'Nikah',
            'services_total_price' => 500,
            'vat' => 5,
            'platform_fee' => 53,
            'total' => 50 + 5 + 500+53,  // nikah plus vat plus services
            'paid_by_platform' => 'Stripe',
            'transaction_id' => 'st_23424242342323',
            'status' => 'Completed'
        ]);
    }
}
