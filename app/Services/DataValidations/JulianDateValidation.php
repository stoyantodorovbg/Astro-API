<?php

namespace App\Services\DataValidations;

class JulianDateValidation extends DataValidation
{
    /**
     * @var string
     */
    protected string $regex = '/^j\d+\.\d+$/';

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
