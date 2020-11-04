<?php

namespace Database\Seeders;

use App\Models\Data;
use App\Models\Configuration;
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
        Data::factory(5)->create();
        Configuration::factory(5)->create();
    }
}
