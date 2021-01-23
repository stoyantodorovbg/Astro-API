<?php

namespace App\Services\Interfaces;

interface FormatDataServiceInterface
{
    /**
     * Return data in certain format
     * Expects 'json', 'csv', 'array' formats
     *
     * @param string $format
     * @param $data
     * @return mixed
     */
    public function formatData(string $format, $data);

    /**
     * Format the result of Swetest command execution
     *
     * @param array $data
     * @return array
     */
    public function formatSwetestResult(array $data): array;
}
