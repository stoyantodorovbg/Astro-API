<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Carbon;

interface ConvertDateServiceInterface
{
    /**
     * Parse a date string to Carbon instance by given date format
     *
     * @param string $date
     * @param string $format
     * @return Carbon
     */
    public function createCarbonFromString(string $date, string $format): Carbon;

    /**
     * Convert a Carbon instance to julian date number
     *
     * @param Carbon $dateTime
     * @return float
     */
    public function convertToJulianNumeric(Carbon $dateTime): float;

    /**
     * Convert an Carbon instance by given time zone
     *
     * @param Carbon $dateTime
     * @param string $timeZone
     * @return Carbon
     */
    public function convertByTimeZone(Carbon $dateTime, string $timeZone = 'UTC'): Carbon;
}
