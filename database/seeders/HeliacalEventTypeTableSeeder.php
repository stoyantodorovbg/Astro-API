<?php

namespace Database\Seeders;

use App\Models\HeliacalEventType;
use Illuminate\Database\Seeder;

class HeliacalEventTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $heliacalEventsData = ['heliacal rising', 'heliacal setting', 'evening first', 'morning last'];

        foreach ($heliacalEventsData as $heliacaEvent) {
            HeliacalEventType::factory()->create([
                'name' => $heliacaEvent,
            ]);
        }
    }
}
