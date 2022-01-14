<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\ExtractDataServiceInterface;
use App\Services\Interfaces\CalculateDataServiceInterface;

class FormatDataService implements ServiceInterface, FormatDataServiceInterface
{
    /**
     * @var array|string[]
     */
    public array $acceptableFormats = [
        'array',
        'csv',
        'json',
    ];

    /**
     * @var array|string[]
     */
    protected array $redundantRowsKeys = [
        'swetest', 'date', 'UT:', 'ET:', 'geo.', 'Epsilon', 'Nutation', 'Houses', 'heliacalEvents'
    ];

    /**
     * Return data in certain format
     * Expects 'json', 'csv', 'array' formats
     *
     * @param string $format
     * @param $data
     * @return mixed
     */
    public function formatData(string $format, $data)
    {
        if (resolve(ValidationServiceInterface::class)->containsExactValue($this, $format, 'acceptableFormats')) {
            $className = "App\\Services\\DataFormats\\" . ucfirst($format) . 'DataFormat';
            $dataFormat = new $className($data);

            return $dataFormat->getData();
        }

        return false;
    }

    /**
     * Format the result of Swetest command execution
     *
     * @param array $data
     * @return array
     */
    public function formatSwetestResult(array $data): array
    {
        $formattedData = [];
        foreach ($data as $row) {
            if (!in_array(explode(' ', $row)[0], $this->redundantRowsKeys, true)) {
                $formattedData[] = preg_replace('/\s+/',' ', $row);
            }
        }

        return $formattedData;
    }

    /**
     * Format is night data for the API response
     *
     * @param $data
     * @return bool|null
     */
    public function isNightResult($data): ?bool
    {
        $isNightData = [];
        foreach ($data as $row) {
            if (strpos($row, 'Sun') !== false) {
                $isNightData['sun'] = str_replace(' ', '', $row);
            }
            if (strpos($row, 'house  1') !== false) {
                $isNightData['house 1'] = str_replace(' ', '', $row);
            }
            if (strpos($row, 'house  7') !== false) {
                $isNightData['house 7'] = str_replace(' ', '', $row);
                break;
            }
        }

        if (!isset($isNightData['house 1'], $isNightData['house 7'], $isNightData['sun'])) {
            return null;
        }

        $calculateDataService = resolve(CalculateDataServiceInterface::class);
        $extractDataService = resolve(ExtractDataServiceInterface::class);

        return $calculateDataService->isNight(
            $extractDataService->floatFromText($isNightData['house 1'], ["'"], [['house1', '°'], ['', '.']]),
            $extractDataService->floatFromText($isNightData['house 7'], ["'"], [['house7', '°'], ['', '.']]),
            $extractDataService->floatFromText($isNightData['sun'], ["'"], [['Sun', '°'], ['', '.']])
        );
    }
}
