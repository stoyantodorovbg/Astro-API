<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\ServiceInterface;

class ConvertDateService implements ServiceInterface, ConvertDateServiceInterface
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
