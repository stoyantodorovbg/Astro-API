<?php

namespace Database\Seeders;

use App\Models\Planet;
use Illuminate\Database\Seeder;

class PlanetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $planetsData = ['Sun', 'Moon', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 'C' => 'Earth'];

        foreach ($planetsData as $key => $planet) {
            Planet::factory()->create([
                'name' => $planet,
                'code' => $key,
            ]);
        }
    }
}
