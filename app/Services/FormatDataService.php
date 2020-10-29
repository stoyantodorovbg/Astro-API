<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;

class FormatDataService implements ServiceInterface, FormatDataServiceInterface
{
    public array $acceptableFormats = [
        'array',
        'csv',
        'json',
    ];

    public function formatData(string $format, $data)
    {
        if (resolve(ValidationServiceInterface::class)->containsExactValue($this, $format, 'acceptableFormats')) {
            $className = "App\\Services\\DataFormats\\" . ucfirst($format) . 'DataFormat';
            $dataFormat = new $className($data);

            return $dataFormat->getData();
        }

        return false;
    }
}
