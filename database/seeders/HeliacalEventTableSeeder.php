<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\City;
use App\Models\Date;
use App\Models\Planet;
use App\Models\HeliacalEvent;
use Illuminate\Database\Seeder;
use App\Models\HeliacalEventType;
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

        $planets = Planet::whereIn('code', ['1', '2', '3', '4', '5', '6'])->get()->toArray();
        $planetsData = [];
        foreach($planets as $planet) {
            $planetsData[$planet['name']] = $planet;
        }

        $heliacalEventTypes = HeliacalEventType::all()
            ->map(function ($item) {
                $item->name = str_replace(' ', '', $item->name);
                return $item;
            })
            ->pluck('id', 'name')
            ->toArray();

        $eventRepo = resolve(HeliacalEventRepositoryInterface::class);

        Date::whereRaw('id > 719964')->chunk(20, function ($dates) use ($dateService, $planetsData, $heliacalEventTypes, $eventRepo) {
            foreach ($dates as $date) {
                dump($date->date);
                dump('-------------------------');
                City::whereRaw('status = 1')->chunk(250, function ($cities) use ($date, $dateService, $planetsData, $heliacalEventTypes, $eventRepo) {
                    $eventsData = [];
                    $heliacalEventsData = $eventRepo->getHeliacalEventCounts($date->date, $cities);
                    foreach ($cities as $city) {
                        foreach ($planetsData as $planet) {
                            if (!isset($heliacalEventsData[$planet['id']][$city->id])) {
                                exec('swetest -bj' .
                                    $dateService->convertToJulianNumeric(Carbon::createFromFormat('Y-m-d H:i:s', $date->date . ' 00:00:01')) .
                                    ' -p' . $planet['code']  .
                                    ' -geopos' . $city->long . ',' . $city->lat .
                                    ' -hev',
                                    $events
                                );

                                unset($events[0]);
                                foreach ($events as $event) {
                                    $data = explode(': ', $event);
                                    $names = explode(' ', $data[0]);
                                    $expectedAt = trim(explode('UT', $data[1])[0]);
                                    if ($names[0] === 'no' ||
                                        $data[2][0] === '-' ||
                                        $expectedAt[0] === '-'
                                    ) {
                                        dump('---' . $city->id);
                                        continue;
                                    }

                                    $eventsData[] = [
                                        'planet_id'   => $planetsData[$names[0]]['id'],
                                        'expected_at' => str_replace(['/', '   '], ['-', ' '], $expectedAt),
                                        'city_id'     => $city->id,
                                        'type_id'     => $heliacalEventTypes[$names[1] . $names[2]],
                                        'visible_for' => trim($data[2]),
                                    ];
                                }

                                unset($events);
                            }
                        }
                    }

                    if ($count = count($eventsData)) {
                        dump($count);
//                        HeliacalEvent::upsert($eventsData, ['expected_at', 'planet_id', 'type_id', 'city_id']);
                        HeliacalEvent::insert($eventsData);
                    }
                });
            }
        });
    }
}
