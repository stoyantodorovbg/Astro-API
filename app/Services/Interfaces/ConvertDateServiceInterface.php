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
     * @param string $timeZone
     * @return mixed
     */
    public function createCarbonFromString(string $date, string $format = 'Y-m-d H:i:s', $timeZone = 'UTC');

    /**
     * Convert a Carbon instance to julian date number
     *
     * @param $dateTime
     * @return float
     */
    public function convertToJulianNumeric($dateTime): float;

    /**
     * Convert an Carbon instance by given time zone
     *
     * @param Carbon $dateTime
     * @param string $timeZone
     * @return Carbon
     */
    public function convertByTimeZone(Carbon $dateTime, string $timeZone = 'UTC'): Carbon;

    /**
     * Get a formated string from carbon instance
     *
     * @param Carbon $carbon
     * @param string $format
     * @return string
     */
    public function formatCarbon(Carbon $carbon, string $format = 'm/d/Y H:i:s'): string;
}
