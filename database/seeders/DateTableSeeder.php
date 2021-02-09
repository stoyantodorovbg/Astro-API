<?php

namespace Database\Seeders;

use App\Models\Date;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $date = $now->copy()->subCenturies(20)->subYears(21)->subMonths(1);

        $data = [];

        while ($date->lessThan($now)) {
            $date->addDay();
            $data[] = ['date' => $date->toDateString()];

            if (count($data) > 10000) {
                Date::insert($data);
                $data = [];
            }
        }

        $data = [];
        $limit = $now->copy()->addCenturies(49);

        while ($now->lessThan($limit)) {
            $now->addDay();
            $data[] = ['date' => $now->toDateString()];

            if (count($data) > 10000) {
                Date::insert($data);
                $data = [];
            }
        }
    }
}
