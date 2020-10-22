<?php

namespace App\Services;

use App\Services\Interfaces\ConvertDateServiceInterface;
use Illuminate\Support\Carbon;

class ConvertDateService implements ConvertDateServiceInterface
{

    public function createCarbonFromString(string $date, string $format): Carbon
    {
        // TODO: Implement createCarbonFromString() method.
    }

    public function convertToJulianNumeric(Carbon $dateTime): float
    {
        // TODO: Implement convertToJulianNumeric() method.
    }

    public function convertByTimeZone(Carbon $dateTime, string $timeZone = 'UTC'): Carbon
    {
        // TODO: Implement convertByTimeZone() method.
    }
}
