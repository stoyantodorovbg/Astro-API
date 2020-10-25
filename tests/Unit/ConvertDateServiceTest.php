<?php

namespace Tests\Unit;

use App\Services\Interfaces\ConvertDateServiceInterface;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ConvertDateServiceTest extends TestCase
{
    /** @test */
    public function create_carbon_from_string_method_returns_a_carbon_instance_by_given_string()
    {
        $carbon = resolve(ConvertDateServiceInterface::class)->createCarbonFromString('2002-12-20 03:03:30');

        $this->assertInstanceOf(Carbon::class, $carbon);

        $this->assertSame('2002-12-20 03:03:30', $carbon->toDateTimeString());
    }

    /** @test */
    public function create_carbon_from_string_method_returns_a_carbon_instance_by_given_string_and_format()
    {
        $carbon = resolve(ConvertDateServiceInterface::class)->createCarbonFromString('20-12-2002 03:03:30', 'd-m-Y H:i:s');

        $this->assertInstanceOf(Carbon::class, $carbon);

        $this->assertSame('2002-12-20 03:03:30', $carbon->toDateTimeString());
    }

    /** @test */
    public function convert_to_julian_numeric_method_converts_date_to_julian_date_number()
    {
        $convertDateService = resolve(ConvertDateServiceInterface::class);

        $julianDateTimeNumber = $convertDateService->convertToJulianNumeric('2020-12-30 12:34:12');

        $unixTimeStamp = jdtounix($julianDateTimeNumber);

        $carbon = Carbon::createFromTimestamp($unixTimeStamp);

        $this->assertSame($carbon->toDateString(), '2020-12-30');

        $julianDateTimeNumber = $convertDateService->convertToJulianNumeric($convertDateService->createCarbonFromString('2020-12-30 12:34:12'));

        $unixTimeStamp = jdtounix($julianDateTimeNumber);

        $carbon = Carbon::createFromTimestamp($unixTimeStamp);

        $this->assertSame($carbon->toDateString(), '2020-12-30');
    }

    /** @test */
    public function convert_by_timezone_method_converts_a_carbon_nstance_by_given_time_zone()
    {
        $convertDateService = resolve(ConvertDateServiceInterface::class);

        $carbon = $convertDateService->createCarbonFromString('2020-12-30 12:34:12');

        $converted = $convertDateService->convertByTimeZone($carbon, 'Europe/Sofia');

        $this->assertSame($converted->getTimezone()->toRegionName(), 'Europe/Sofia');
    }
}
