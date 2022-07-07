<?php

namespace App\Services;

use App\Models\City;
use Illuminate\Support\Carbon;
use App\Services\Interfaces\CalculateDataServiceInterface;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;

class CalculateDataService implements CalculateDataServiceInterface
{
    /**
     * @var array
     */
    protected $baseEquinoxDifferences = [
        'sidereal' => 25.03,
    ];

    /**
     * @var string
     */
    protected $baseEquinoxDate = '2021-03-20 00:00:00';

    /**
     * @var string
     */
    protected $baseEquinox = '-03-20 00:00:00';

    /**
     * @var HeliacalEventRepositoryInterface
     */
    protected $heliacalEventRepository;

    /**
     * @var Carbon
     */
    protected $requestedDate;

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
     * @param array $dataQuery
     * @param bool $isNight
     * @return string
     */
    public function currentMoonMonth(City $city, array $dataQuery, bool $isNight): string
    {
        $heliacalEventRepository = $this->getHeliacalEventRepository();
        $requestedDate = $this->getRequestedDayAsCarbon($dataQuery['date'])->copy()->endOfDay();
        $requestedYear = $requestedDate->year;

        if (!$moonYearBeginning = $this->getMoonYearBeginning($city, $requestedYear)) {
            return '';
        }

        $moonYearBeginning = Carbon::createFromFormat('Y-m-d H:i:s', $moonYearBeginning)->endOfDay();
        if ($requestedDate->lessThan($moonYearBeginning)) {
            $moonYearBeginning = Carbon::createFromFormat('Y-m-d H:i:s', $this->getMoonYearBeginning($city, $requestedYear -1))->endOfDay();
        }

        $month = 0;
        for ($i = 0; $i < 12; $i++) {
            $sameDate = $moonYearBeginning->equalTo($requestedDate);

            if ($isNight && $sameDate) {
                $month++;
                break;
            }

            if ($sameDate || $moonYearBeginning->greaterThan($requestedDate)) {
                break;
            }

            $moonYearBeginning->addDays(29);
            $month++;
            $heliacalEvent = $heliacalEventRepository->getNextHeliacalEvent(2, $city->id, $moonYearBeginning);

            while (($heliacalEvent) && $heliacalEvent->type_id === 3 && $moonYearBeginning->lessThan(Carbon::parse($heliacalEvent->expected_at)->endOfDay())) {
                $moonYearBeginning->addDay();
                $heliacalEvent = $heliacalEventRepository->getNextHeliacalEvent(2, $city->id, $moonYearBeginning);
            }
        }

        $month = $month >= 1 ? $month : 12;

        return (string) $month;
    }

    /**
     * Get the beginning of Moon year
     *
     * @param City $city
     * @param int $year
     * @return string
     */
    public function getMoonYearBeginning(City $city, int $year): string
    {
        $month = 3;

        while (!($date = $this->searchForMoonYearBeginning($city, $year, $month))) {
            $month++;
            if ($month > 12) {
                return false;
            }
        }

        return $date;
    }

    /**
     * Get current Moon day number
     *
     * @param City $city
     * @param array $dataQuery
     * @return int|null
     */
    public function currentMoonDay(City $city, array $dataQuery)
    {
        $heliacalEventRepository = $this->getHeliacalEventRepository();
        $requestedDate = $this->getRequestedDayAsCarbon($dataQuery['date']);

        if ($lastEveningFirst = $heliacalEventRepository->getLastHeliacalEvent( 2, $city->id, 3, $requestedDate)) {
            $lastEveningFirstDate = Carbon::createFromDate($lastEveningFirst->expected_at);

            return $lastEveningFirstDate->diffInDays($requestedDate) + 1;
        }

        return null;

    }

    /**
     * Check if the horoscope is night
     *
     * @param float $asc
     * @param float $desc
     * @param float $sun
     * @return bool
     */
    public function isNight(float $asc, float $desc, float $sun): bool
    {
        if (($asc < $desc && $sun > $asc && $sun < $desc) ||
            ($asc > $desc &&
                (($sun > $asc && $sun > $desc) || ($sun < $desc && $sun < $asc))
            )
        ) {
            return true;
        }

        return false;
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
        $heliacalEventRepository = $this->getHeliacalEventRepository();
        $date = Carbon::createFromDate($year, $month, 1);
        $heliacalEventData = $heliacalEventRepository->getHeliacalEventsData($city, $date);
        $baseEquinoxDate = $year . $this->baseEquinox;

        if (!isset($heliacalEventData['data']) || !isset($heliacalEventData['data']['Moon'])) {
            return false;
        }

        foreach ($heliacalEventData['data']['Moon'] as $event) {
            if ($event->type === 'evening first' && $event->expected_at > $baseEquinoxDate) {
                return $event->expected_at;
            }
        }

        return false;
    }

    /**
     * Get Heliacal event repository
     *
     * @return HeliacalEventRepositoryInterface
     */
    protected function getHeliacalEventRepository()
    {
        if (!$this->heliacalEventRepository) {
            $this->setHeliacalEventRepository();
        }

        return $this->heliacalEventRepository;
    }

    /**
     * Set Heliacal event repository
     *
     * @return void
     */
    protected function setHeliacalEventRepository()
    {
        $this->heliacalEventRepository = resolve(HeliacalEventRepositoryInterface::class);
    }

    /**
     * Get requested date as Carbon instance
     *
     * @param string $date
     * @return Carbon
     */
    protected function getRequestedDayAsCarbon(string $date)
    {
        if (!$this->requestedDate) {
            $this->setRequestedDayAsCarbon($date);
        }

        return $this->requestedDate;
    }

    /**
     * Set requested date as Carbon instance
     *
     * @param string $date
     * @return void
     */
    protected function setRequestedDayAsCarbon(string $date)
    {
        $this->requestedDate = Carbon::createFromFormat('Y-m-d H:i:s', str_replace('T', ' ', $date), 'UTC');
    }
}
