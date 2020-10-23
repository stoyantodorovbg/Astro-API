<?php

namespace App\Services\Interfaces;

interface ValidationServiceInterface
{
    /**
     * Check if all inputs have exact values
     *
     * @param ServiceInterface $service
     * @param array $data
     * @return bool
     */
    public function containsExactValues(ServiceInterface $service, array $data): bool;

    /**
     * Check for exact value
     *
     * @param ServiceInterface $service
     * @param string $input
     * @param string $type
     * @return bool
     */
    public function containsExactValue(ServiceInterface $service, string $input, string $type): bool;
}
