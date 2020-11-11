<?php

namespace App\Services\DataValidations;

class CoordinatesValidation extends DataValidation
{
    /**
     * @var string
     */
    protected string $regex = '/^\d+\.\d+,\d+\.\d+$/';

    /**
     * Check if the given data is valid
     *
     * @param $data
     * @return bool
     */
    public function isValid($data): bool
    {
        return $this->regexValidation($data);
    }
}
