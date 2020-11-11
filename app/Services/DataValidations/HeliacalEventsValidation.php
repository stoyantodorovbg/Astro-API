<?php

namespace App\Services\DataValidations;

class HeliacalEventsValidation extends DataValidation
{
    /**
     * @var array|string[]
     */
    protected array $arrayValidation = [
        '0',
        '1',
        '2',
        '3',
        '4',
    ];

    /**
     * Check if the given data is valid
     *
     * @param $data
     * @return bool
     */
    public function isValid($data): bool
    {
        return $this->checkArray(str_split($data));
    }
}
