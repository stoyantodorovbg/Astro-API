<?php

namespace App\Repositories\Interfaces;

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
}
