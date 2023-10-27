<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Services::create([
            'name' => 'Nikah Certificate (print)',
            'slug' => 'print',
            'description' => 'Get two ICC-certified hardcopies of your Nikah Certificate delivered to your home.',
            'price'=> 25.00,
        ]);
        Services::create([
            'name' => 'Nikah Video',
            'slug' => 'video',
            'description' => 'Get the video of the Nikah ceremony (file download).',
            'price'=> 9.0,
        ]);

        Services::create([
            'name' => 'Nikah with Wali',
            'slug' => 'nikah_with_wali',
            'description' => 'If selected, the bride will have to invite her legal custodian.',
            'price'=> 0.00,
        ]);

        Services::create([
            'name' => 'Nikah with Wakil',
            'slug' => 'nikah_with_wakeel',
            'description' => 'If selected, the Imam will act as a Wakil.',
            'price'=> 0.00,
        ]);

        Services::create([
            'name' => 'Your Own Witnesses',
            'slug' => 'own_witness',
            'description' => 'If selected, you will need to invite two male witnesses.',
            'price'=> 0.00,
        ]);

        Services::create([
            'name' => 'Nikah provided Witnesses',
            'slug' => 'nikah_witness',
            'description' => 'If selected, MyNikahNow will provide two witnesses.',
            'price'=> 40.00,
        ]);

        Services::create([
            'name' => 'Nikah provided Imam',
            'description' => 'MyNikahNow will provide Imam.	',
            'price'=> 0.00,
        ]);
    }
}
