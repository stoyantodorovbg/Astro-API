<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Interfaces\FormatDataServiceInterface;

class FormatDataServiceTest extends TestCase
{
    /** @test */
    public function format_data_method_returns_false_when_receives_unsupported_format()
    {
        $formatDataService = resolve(FormatDataServiceInterface::class);

        self::assertFalse($formatDataService->formatData('excel', 'test'));
    }

    /** @test */
    public function format_data_method_returns_an_array_when_receives_an_array()
    {
        $formatDataService = resolve(FormatDataServiceInterface::class);

        self::assertSame(['test'], $formatDataService->formatData('array', ['test']));
    }

    /** @test */
    public function format_data_method_returns_an_array_when_receives_a_text()
    {
        $formatDataService = resolve(FormatDataServiceInterface::class);

        $commandResult = shell_exec('swetest -p2 -b1.12.1900 -n3 -s2');
        $expectedData = [
            "swetest -p2 -b1.12.1900 -n3 -s2 ",
            "date (dmy) 1.12.1900 greg.   0:00:00 ET\t\tversion 1.80.00",
            "ET: 2415354.50000000000",
            "Epsilon (true)    23°27' 3.1431",
            "Nutation           0° 0'14.2415   -0° 0' 4.6685",
            "Mercury          230°13'29.5475    2°37' 3.8851    0.838595114    0°11'18.6242",
            "Mercury          230°54'39.4749    2°37'30.5003    0.887145930    0°29'16.6773",
            "Mercury          232° 8'17.4751    2°33' 5.7839    0.936156035    0°43'47.6249",
        ];

        self::assertSame($expectedData, $formatDataService->formatData('array', $commandResult));
    }

    /** @test */
    public function format_data_method_returns_an_array_when_receives_a_valid_json()
    {
        $formatDataService = resolve(FormatDataServiceInterface::class);
        $data = '{"title": "example data"}';
        $expectedResult = ['title' => 'example data'];

        self::assertSame($expectedResult, $formatDataService->formatData('array', $data));
    }
}
