<?php

namespace App\Services\DataValidations;

class DateValidation extends DataValidation
{
    /**
     * Check if the given data is valid
     *
     * @param $data
     * @return bool
     */
    public function isValid($data): bool
    {
        return true;
    }
}
