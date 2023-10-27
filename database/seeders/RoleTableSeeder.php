<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Role::create([
             'name' => 'admin',
             'guard_name' => 'web',
         ]);
         Role::create([
             'name' => 'imam',
             'guard_name' => 'web',
         ]);
         Role::create([
             'name' => 'user',
             'guard_name' => 'web',
         ]);
        // Role::create([
        //     'name' => 'walli',
        //     'guard_name' => 'web',
        // ]);
        // Role::create([
        //     'name' => 'wakeel',
        //     'guard_name' => 'web',
        // ]);
        // Role::create([
        //     'name' => 'witness',
        //     'guard_name' => 'web',
        // ]);
    }
}
