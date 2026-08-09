<?php

namespace Database\Seeders;

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
        // $this->call(subAccountSeeder::class);
        // $this->call(FixUserAccountNumbersSeeder::class);
        \App\Models\User::factory(20)->create();
        \App\Models\Location::factory(6)->create();
        \App\Models\Station::factory(6)->create();
        \App\Models\CarBrand::factory(20)->create();
        \App\Models\PriceSetup::factory(20)->create();
        \App\Models\Vehicle::factory(10)->create();
        \App\Models\Photo::factory(50)->create();
        

    }
}
