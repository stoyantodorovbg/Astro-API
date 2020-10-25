<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;

class ValidationService implements ServiceInterface, ValidationServiceInterface
{
    /**
     * Check if all inputs have exact values
     *
     * @param ServiceInterface $service
     * @param array $data
     * @return bool
     */
    public function containsExactValues(ServiceInterface $service, array $data): bool
    {
        foreach ($data as $key => $values) {
            foreach ($values as $value) {
                if (!$this->containsExactValue($service, $value, $key)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check for exact value
     *
     * @param ServiceInterface $service
     * @param string $input
     * @param string $type
     * @return bool
     */
    public function containsExactValue(ServiceInterface $service, string $input, string $type): bool
    {
        if (!in_array($input, $service->$type, true)) {
            return false;
        }

        return true;
    }
}
