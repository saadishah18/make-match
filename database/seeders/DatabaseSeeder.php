<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Nikah;
use App\Models\NikahDetailHistory;
use App\Models\NikahType;
use App\Models\PortalSetting;
use App\Models\Talaq;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ServicesSeeder::class);
//        $this->call(PartnerDetailSeeder::class);
        $this->call(NikkahTypeSeeder::class);
        $this->call(PortalSettingSeeder::class);
//        $this->call(NikahSeeder::class);
//        $this->call(NikkahDetailHistorySeeder::class);
//        $this->call(ServiceObtainedSeeder::class);
//        $this->call(PaymentsSeeder::class);
//        $this->call(TalaqSeeder::class);
//        $this->call(RujuSeeder::class);
//        $this->call(KhuluSeeder::class);
    }
}
