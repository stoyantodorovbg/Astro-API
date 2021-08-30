<?php

namespace App\Services;

use App\Models\City;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;
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

    protected $baseEquinoxDate = '2021-03-20 00:00:00';

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

    /**
     * Get current Moon month number
     *
     * @param City $city
     * @param array $tropicalMonthsData
     * @param array $dataQuery
     * @return string
     */
    public function currentMoonMonth(City $city, array $tropicalMonthsData, array $dataQuery): string
    {
        $moonYearBeginning = $this->getMoonYearBeginning($city, $dataQuery);
        $moonYearBeginning = Carbon::createFromFormat('Y-m-d H:i:s', $moonYearBeginning);
        $requestedDate = Carbon::createFromFormat('Y-m-d H:i:s', str_replace('T', ' ', $dataQuery['date']), 'UTC');

        $currentMoonMonth = 1;
        while ($moonYearBeginning->lessThan($requestedDate)) {
            $moonYearBeginning->addDays(28);
            $currentMoonMonth++;
        }

        return (string) $currentMoonMonth;
    }

    /**
     * Get the beginning of Moon year
     *
     * @param City $city
     * @param array $dataQuery
     * @return string
     */
    public function getMoonYearBeginning(City $city, array $dataQuery): string
    {
        $requestedDate = Carbon::parse($dataQuery['date']);
        $year = $requestedDate->year;
        $month = 3;

        while (!($date = $this->searchForMoonYearBeginning($city, $year, $month))) {
            $month++;
        }

        return $date;
    }

    /**
     * Search for the beginning of Moon year
     *
     * @param City $city
     * @param int $year
     * @param int $month
     * @return false|mixed|string
     */
    protected function searchForMoonYearBeginning(City $city, int $year, int $month = 3)
    {
        $heliacalEventRepository = resolve(HeliacalEventRepositoryInterface::class);
        $date = Carbon::createFromDate($year, $month, 1);
        $heliacalEventData = $heliacalEventRepository->getHeliacalEventsData($city, $date);

        if (!isset($heliacalEventData['data']) || !isset($heliacalEventData['data']['Moon'])) {
            return false;
        }

        foreach ($heliacalEventData['data']['Moon'] as $event) {
            if ($event->type === 'evening first' && $event->expected_at > $this->baseEquinoxDate) {
                return $event->expected_at;
            }
        }

        return false;
    }
}
