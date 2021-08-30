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
     * @param array $tropicalMonthsData
     * @param array $dataQuery
     * @return string
     */
    public function currentMoonMonth(City $city,array $tropicalMonthsData, array $dataQuery): string;
}
