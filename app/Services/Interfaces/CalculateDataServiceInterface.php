<?php

namespace App\Services\Interfaces;

interface CalculateDataServiceInterface
{
    /**
     * Get positions of the tropical months for a given zodiacal system
     *
     * @param array $dataQuery
     * @return array
     */
    public function tropicalMonthsData(array $dataQuery): array;
}
