<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Date;
use App\Models\Planet;
use Illuminate\Database\Seeder;
use App\Models\HeliacalEventType;
use Illuminate\Support\Facades\DB;
use App\Jobs\CalculateHeliacalEvents;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;

class HeliacalEventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dateService = resolve(ConvertDateServiceInterface::class);
        $eventRepo = resolve(HeliacalEventRepositoryInterface::class);

        $planetsData = Planet::whereIn('code', ['1', '2', '3', '4', '5', '6'])->get()->keyBy('name')->toArray();

        $heliacalEventTypes = HeliacalEventType::all()
            ->map(function ($item) {
                $item->name = str_replace(' ', '', $item->name);
                return $item;
            })
            ->pluck('id', 'name')
            ->toArray();

        Date::whereRaw('id > 726585')->chunk(360, function ($dates) use ($dateService, $planetsData, $heliacalEventTypes, $eventRepo) {
            foreach ($dates as $date) {
                logger($date->date);
                $cities = City::whereRaw('status = 1')->get();
                $heliacalEventsData = $eventRepo->getHeliacalEventCounts($date->date, $cities);
                $commandBase = 'swetest -bj' . $dateService->convertToJulianNumeric(Carbon::createFromFormat('Y-m-d H:i:s', $date->date . ' 00:00:01')) . ' -p';
                $cities->chunk(1200)->each(function ($citiesChunk) use ($planetsData, $heliacalEventsData, $commandBase, $heliacalEventTypes) {
                    CalculateHeliacalEvents::dispatch($citiesChunk, $planetsData, $heliacalEventsData, $commandBase, $heliacalEventTypes);
                });

                while (DB::table('jobs')->count()) {
                    sleep(3);
                }
            }
        });
    }
}
