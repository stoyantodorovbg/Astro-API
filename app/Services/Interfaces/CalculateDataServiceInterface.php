<?php

namespace App\Services\Interfaces;

use App\Models\City;

interface CalculateDataServiceInterface
{
    /**
     * Get positions of the tropical months for a given zodiacal system
     *
     * @param array $dataQuery
     * @return array
     */
    public function tropicalMonthsData(array $dataQuery): array;

    /**
     * Get current Moon month number
     *
     * @param City $city
     * @param array $dataQuery
     * @param bool $isNight
     * @return string
     */
    public function currentMoonMonth(City $city, array $dataQuery, bool $isNight): string;

    /**
     * Check if the horoscope is night
     *
     * @param float $asc
     * @param float $desc
     * @param float $sun
     * @return bool
     */
    public function isNight(float $asc, float $desc, float $sun): bool;

    /**
     * Get the beginning of Moon year
     *
     * @param City $city
     * @param int $year
     * @return string
     */
    public function getMoonYearBeginning(City $city, int $year): string;

    /**
     * Get current Moon day number
     *
     * @param City $city
     * @param array $dataQuery
     * @return int|null
     */
    public function currentMoonDay(City $city, array $dataQuery);
}
