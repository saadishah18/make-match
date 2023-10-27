<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            // 'role' => '1',
            'email' => 'admin@nikkah.com',
            'password' => bcrypt('nikkah'),
        ]);

        User::create([
            'first_name' => 'Muhammad',
            'last_name' => 'Ali',
            // 'role' => '2',
            'email' => 'ali@nikkah.com',
            'password' => bcrypt('nikkah'),
            'qr_number' => User::generateQRNumber(),
        ]);
    }
}
