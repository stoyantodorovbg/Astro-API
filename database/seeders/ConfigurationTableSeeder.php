<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;

class ConfigurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Configuration::updateOrCreate([
            'name' => 'Houses and Planets'
        ], [
            'description' => 'Get houses and planets data.',
            'command'     => 'swetest -b -p -house -ut',
        ]);
    }
}
