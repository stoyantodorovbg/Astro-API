<?php

namespace App\Repositories\Interfaces;

use App\Models\City;
use Carbon\Carbon as BaseCarbon;
use Illuminate\Support\Collection;

interface HeliacalEventRepositoryInterface
{
    /**
     * Check if the heliacal events for a date are already found
     *
     * @param $date
     * @param int $cityId
     * @param int $planetId
     * @return bool
     */
    public function dataHasFound($date, int $cityId, int $planetId): int;

    /**
     * Get the count of the heliacal events which are expected at after given date
     *
     * @param $date
     * @param Collection $cities
     * @param array|int[] $planetIds
     * @return array
     */
    public function getHeliacalEventCounts($date, Collection $cities, array $planetIds = [2, 3, 4, 5, 6, 7]): array;

    /**
     * Get heliacal events data for given city and date time
     * Return Collection that contains HeliacalEvent distributed by planets
     *
     * @param City $city
     * @param string $dateTime
     *
     * @return array
     */
    public function getHeliacalEventsData(City $city, string $dateTime): array;

    /**
     * Get the next heliacal event by given type, planet, city and date
     *
     * @param int $planetId
     * @param int $cityId
     * @param Carbon $date
     * @return mixed
     */
    public function getNextHeliacalEvent(int $planetId, int $cityId, BaseCarbon $date);

    /**
     * Get the last heliacal event by given type, planet, city and date
     *
     * @param int $planetId
     * @param int $cityId
     * @param int $typeId
     * @param BaseCarbon $date
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getLastHeliacalEvent(int $planetId, int $cityId, int $typeId, BaseCarbon $date);
}
