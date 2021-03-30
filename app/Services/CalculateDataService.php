<?php

namespace App\Services;

use App\Services\Interfaces\CalculateDataServiceInterface;
use Illuminate\Support\Carbon;

class CalculateDataService implements CalculateDataServiceInterface
{
    /**
     * @var array
     */
    protected $baseEquinoxDifferences = [
        'sidereal' => 25.03,
    ];

    protected $baseEquinoxDate = '29-03-2021';

    /**
     * Get positions of the tropical months for a given zodiacal system
     *
     * @param array $dataQuery
     * @return array
     */
    public function tropicalMonthsData(array $dataQuery): array
    {
        $data = [];
        $degree = 0;

        if (isset($dataQuery['siderealMethods'])) {
            $baseEquinoxDateCarbon = Carbon::parse($this->baseEquinoxDate);
            $requestedDate = Carbon::parse($dataQuery['date']);
            $diffInYears = $baseEquinoxDateCarbon->diffInYears($requestedDate);

            if ($requestedDate->gt($baseEquinoxDateCarbon)) {
                $diffInYears = -$diffInYears;
            }

            $data = [];
            $degree = 0 - $diffInYears * 0.0138;

            $degree -= $this->baseEquinoxDifferences['sidereal'] + $diffInYears * 0.0138;
        }

        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = $degree;
            $degree += 30;
        }

        return $data;
    }
}
