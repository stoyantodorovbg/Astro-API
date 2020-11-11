<?php

namespace App\Services\DataValidations;

class HouseTypesValidation extends DataValidation
{
    /**
     * @var array|string[]
     */
    protected array $arrayValidation = [
        'A',
        'B',
        'C',
        'D',
        'E',
        'F',
        'G',
        'H',
        'I',
        'i',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'Q',
        'R',
        'S',
        'T',
        'U',
        'V',
        'W',
        'X',
        'Y',
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
