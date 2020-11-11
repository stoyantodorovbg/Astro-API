<?php

namespace App\Services\DataValidations;

class PlanetsValidation extends DataValidation
{
    /**
     * @var array|string[]
     */
    protected array $arrayValidation = [
        'd',
        'p',
        'h',
        'a',
        '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        'm',
        't',
        'n',
        'o',
	    'q',
	    'y',
	    'b',
        'A',
        'B',
        'c',
        'g',
        'C',
        'F',
        'D',
        'E',
        'G',
        'H',
        'I',
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
