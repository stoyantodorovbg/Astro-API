<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;

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
        'swetest', 'date', 'UT:', 'ET:', 'geo.', 'Epsilon', 'Nutation', 'Houses'
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
        $formattedData[] = [];
        foreach ($data[0] as $key => $row) {
            if (!in_array(explode(' ', $row)[0], $this->redundantRowsKeys, true)) {
                $formattedData[0][] = preg_replace('/\s+/',' ', $row);
            }
        }

        return $formattedData;
    }
}
