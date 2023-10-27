<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        User::factory()->count(20)->create();
//        $users = User::all();
//        foreach ($users as $user){
//            if($user->id == 1){
//                $user->assignRole('admin');
//            }
//            else if($user->id == 2){
//                $user->assignRole('imam');
//            }else{
//                $user->assignRole('user');
//            }
//
//        }
        $random = substr(mt_rand(), 0, 13);
        $user = User::create([
            'remember_token' => Str::random(10),
            'first_name' => 'qasim',
            'last_name' => 'ali',
            'email' => 'sadmin@mynikahnow.co.uk',
            'email_verified_at' => now(),
            'password' => Hash::make('@Dmin123'),
            'phone' => fake()->phoneNumber,
            'phone_verified_at' => now(),
            'address' => fake()->address,
            'gender' => 'male',
            'profile_image' => null,
            'id_card_number' => $random,
            'id_expiry' => '2025-12-31',
            'date_of_birth' => '1994-03-19',
            'id_card_front' => null,
            'id_card_back' => null,
            'selfie' => null,
            'qr_number' => generate_code(6),
            'active_role' => 'admin'
        ]);
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
