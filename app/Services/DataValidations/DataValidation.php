<?php

namespace App\Services\DataValidations;

abstract class DataValidation
{
    /**
     * @var array
     */
    protected array $arrayValidation = [];

    /**
     * @var string
     */
    protected string $regex = '//';

    /**
     * Check if the given data is valid
     *
     * @param $data
     * @return bool
     */
    abstract public function isValid($data): bool;

    /**
     * Check if all elements an array contains all elements in another
     *
     * @param array $data
     * @return bool
     */
    protected function checkArray(array $data): bool
    {
        foreach ($data as $item) {
            if (!in_array($item, $this->arrayValidation, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check for a match in string by regex
     *
     * @param string $data
     * @return bool
     */
    protected function regexValidation(string $data): bool
    {
        return preg_match($this->regex, $data, $array);
    }
}
