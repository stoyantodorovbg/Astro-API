<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface HttpConnectorServiceInterface
{
    /**
     * Connects HTTP parameters to Swetest options and validated theirs values
     * Returns an array with options for Swetest command
     * Returns an array with key "error" when something when wrong
     *
     * @param array $options
     * @return array
     */
    public function connectSwetestOptions(array $options): array;
}
