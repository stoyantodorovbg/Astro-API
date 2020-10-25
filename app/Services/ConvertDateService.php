<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\ServiceInterface;

class ConvertDateService implements ServiceInterface, ConvertDateServiceInterface
{

    /**
     * Parse a date string to Carbon instance by given date format
     *
     * @param string $date
     * @param string $format
     * @param string $timeZone
     * @return mixed
     */
    public function createCarbonFromString(string $date, string $format = 'Y-m-d H:i:s', $timeZone = 'UTC')
    {
        return Carbon::createFromFormat($format, $date, $timeZone);
    }

    /**
     * Receives Carbon instance or date time string and return julian date time number or false
     *
     * @param $dateTime
     * @return float
     */
    public function convertToJulianNumeric($dateTime): float
    {
        if ($dateTime instanceof Carbon) {
            $dateTime = $dateTime->toDateTimeString();
        }

        if ($timestamp = strtotime($dateTime)) {
            return $timestamp / 86400 + 2440587.5;
        }

        return false;
    }

    /**
     * Convert an Carbon instance by given time zone
     *
     * @param Carbon $dateTime
     * @param string $timeZone
     * @return Carbon
     */
    public function convertByTimeZone(Carbon $dateTime, string $timeZone = 'UTC'): Carbon
    {
        return $dateTime->setTimezone($timeZone);
    }

    /**
     * Get a formatted string from Carbon instance
     *
     * @param Carbon $carbon
     * @param string $format
     * @return string
     */
    public function formatCarbon(Carbon $carbon, string $format = 'm/d/Y H:i:s'): string
    {
        return $carbon->format($format);
    }
}
