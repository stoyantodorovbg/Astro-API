<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Planet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;

class HeliacalEventRepository implements HeliacalEventRepositoryInterface
{
    /**
     * Check if the heliacal events for a date are already found
     *
     * @param $date
     * @param int $cityId
     * @param int $planetId
     * @return bool
     */
    public function dataHasFound($date, int $cityId, int $planetId): int
    {
        return DB::table('heliacal_events')->selectRaw('count(*) as count')
            ->whereRaw("`city_id` = '$cityId' and `planet_id` = '$planetId' and `expected_at` > '$date'")
            ->get()[0]->count;
    }

    /**
     * Get the count of the heliacal events which are expected at after given date
     *
     * @param $date
     * @param Collection $cities
     * @param array|int[] $planetIds
     * @return array
     */
    public function getHeliacalEventCounts($date, Collection $cities, array $planetIds = [2, 3, 4, 5, 6, 7]): array
    {
        $citiesIds = $cities->pluck(0, 'id')->toArray();
        $data = [];
        $firstCityId = $cities->first()->id;
        $lastCityId = $cities->last()->id;
        foreach ($planetIds as $planetId) {
            $citiesIdsCopy = $citiesIds;
            DB::table('heliacal_events')
                ->selectRaw('city_id')
                ->whereRaw("expected_at > '$date'
                    and planet_id = $planetId
                    and city_id >= $firstCityId
                    and city_id <= $lastCityId")
                ->pluck('city_id')
            ->each(function ($item) use (&$citiesIdsCopy) {
                 $citiesIdsCopy[$item] = 1;
            });

            $data[$planetId] = $citiesIdsCopy;
        }

        return $data;
    }

    /**
     * Get heliacal events data for given city and date time
     * Return Collection that contains HeliacalEvent distributed by planets
     *
     * @param City $city
     * @param string $dateTime
     *
     * @return array
     */
    public function getHeliacalEventsData(City $city, string $dateTime): array
    {
        if (Cache::has('heliacal_events_' . $city->name . $dateTime)) {
            return Cache::get('heliacal_events_' . $city->name . $dateTime);
        }

        $helicalEvents = ['cityName' => $city->name, 'data' => []];
        $planets = Planet::whereIn('id', [2, 3, 4, 5, 6, 7])->get();
        foreach ($planets as $planet) {
            $helicalEvents['data'][$planet->name] = DB::table('heliacal_events')
                ->selectRaw('heliacal_events.expected_at, heliacal_event_types.name as type')
                ->join('heliacal_event_types', 'heliacal_events.type_id', '=', 'heliacal_event_types.id')
                ->whereRaw('planet_id = ' . $planet->id . ' and city_id = ' . $city->id . ' and expected_at <= "' . $dateTime . '"')
                ->orderBy('expected_at', 'DESC')
                ->limit(1)
                ->get();

            $helicalEvents['data'][$planet->name] = $helicalEvents['data'][$planet->name]->merge(DB::table('heliacal_events')
                ->selectRaw('heliacal_events.expected_at, heliacal_event_types.name as type')
                ->join('heliacal_event_types', 'heliacal_events.type_id', '=', 'heliacal_event_types.id')
                ->whereRaw('planet_id = ' . $planet->id . ' and city_id = ' . $city->id . ' and expected_at > "' . $dateTime . '"')
                ->orderBy('expected_at', 'ASC')
                ->limit(3)
                ->get());

            $helicalEvents['data'][$planet->name] = $helicalEvents['data'][$planet->name]->toArray();
        }

        Cache::put('heliacal_events_' . $city->name . $dateTime, $helicalEvents);

        return $helicalEvents;
    }
}
