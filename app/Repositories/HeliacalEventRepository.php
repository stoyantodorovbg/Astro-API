<?php

namespace App\Repositories;

use App\Models\HeliacalEvent;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
        $data = [];

            foreach ($planetIds as $planetId) {
                $data[$planetId] = [];
                    $data[$planetId] = DB::table('heliacal_events')
                    ->selectRaw('city_id, planet_id')
                    ->whereRaw("planet_id = $planetId and expected_at > '$date'")
                    ->whereIn('city_id', $cities->pluck('id')->toArray())
                    ->groupByRaw('city_id, planet_id')
                    ->pluck('planet_id', 'city_id')
                    ->toArray();
        }

        return $data;
    }
}
