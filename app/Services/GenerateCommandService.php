<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;

class GenerateCommandService implements ServiceInterface, GenerateCommandServiceInterface
{
    /**
     * @var array|string[]
     */
    public array $acceptableCommands = [
        'swetest',
    ];

    /**
     * @var array|string[]
     */
    public array $acceptableOptions = [
        'hplan',
        'bj',
        'solecl',
        'occult',
        'local',
        'lunecl',
        'hev',
        'rise',
        'metr',
        'total',
        'partial',
        'annular',
        'anntot',
        'penumbral',
        'central',
        'noncentral',
        'norefrac',
        'disccenter',
        'discbottom',
        'hindu',
        'p',
        'xf',
        'house',
        'ut',
        'sid',
        'hel',
        'bary',
        'topo',
        'f',
        's',
        'n'
    ];

    /**
     * @var array|string[]
     */
    public array $acceptableArguments = [];


    /**
     * Create a command line by given command name, command arguments and command short (-) options
     *
     * @param string $command
     * @param array $options
     * @param array $arguments
     * @return false|mixed
     */
    public function generateCommand(string $command, array $options = [], array $arguments = [])
    {
        $validationData = [
            'acceptableCommands'  => [$command],
            'acceptableOptions'   => array_keys($options),
            'acceptableArguments' => $arguments,
        ];

        if (resolve(ValidationServiceInterface::class)->containsExactValues($this, $validationData)) {
            if ($options) {
                foreach ($options as $key => $value) {
                    $command .= ' -' . $key . $value;
                }
            }

            if ($arguments) {
                foreach ($arguments as $argument) {
                    $command .= ' ' . $argument;
                }
            }

            return $command;
        }

        return false;
    }

    /**
     * Add values to an option
     *
     * @param $swetestOption
     * @param array $parameterValuesData
     * @param array $optionsValues
     * @param string $parameterKey
     * @return string
     */
    public function addOptionValues($swetestOption, array $parameterValuesData, array $optionsValues, string $parameterKey): string
    {
        $optionValue = '';
        $prefix = $swetestOption === 'house' || $swetestOption === 'topo' ? ',' : '';
        foreach ($parameterValuesData as $parameter) {
            if (isset($optionsValues[$parameterKey]) && isset($optionsValues[$parameterKey][$parameter])) {
                $optionValue .= $optionsValues[$parameterKey][$parameter] . $prefix;
            } else {
                $optionValue .= $parameter . $prefix;
            }
        }

        return $optionValue;
    }
}
