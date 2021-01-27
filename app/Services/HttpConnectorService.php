<?php

namespace App\Services;

use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;
use App\Services\Interfaces\HttpConnectorServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;

class HttpConnectorService implements HttpConnectorServiceInterface
{
    /**
     * Connects HTTP parameters to Swetest options and validated theirs values
     * Returns an array with options for Swetest command
     * Returns an array with key "error" when something when wrong
     *
     * @param array $options
     * @param GenerateCommandServiceInterface $generateCommandService
     * @return array
     */
    public function connectSwetestOptions(array $options, GenerateCommandServiceInterface $generateCommandService): array
    {
        $connectedOptions = [];
        $optionsKeys = config('swetest.httpMapping.optionsKeys');
        $validationsOptions = config('swetest.validations.options');
        $optionsValues = config('swetest.httpMapping.optionsValues');

        foreach ($options as $parameterKey => $parameterValues) {
            if (array_key_exists($parameterKey, $optionsKeys)) {
                $swetestOption = $optionsKeys[$parameterKey];

                // convert values
                $parameterValuesData = explode(',', $parameterValues);

                // validation
                if ($error = resolve(ValidationServiceInterface::class)->validateSwetestOptions(
                    $swetestOption,
                    $parameterKey,
                    $validationsOptions,
                    $parameterValuesData)
                ) {
                    return $error;
                }

                // add option values
                $optionValue = $generateCommandService->addOptionValues(
                    $swetestOption,
                    $parameterValuesData,
                    $optionsValues,
                    $parameterKey
                );

                // convert to Julian date
                if ($swetestOption === 'bj') {
                    $optionValue = resolve(ConvertDateServiceInterface::class)->convertToJulianNumeric(
                        $parameterValues
                    );
                }

                // Process fixed star parameter
                if ($swetestOption === 'xf' && isset($connectedOptions['p'])) {
                    $connectedOptions['p'] .= 'f';
                }

                //add options values
                $connectedOptions[$swetestOption] = $optionValue;

                // add ut option because it is required for house option
                if ($swetestOption === 'house') {
                    $connectedOptions['ut'] = '';
                }
            }
        }

        return $connectedOptions;
    }

    /**
     * @param $swetestOption
     * @param array $parameterValuesData
     * @param \Illuminate\Config\Repository $optionsValues
     * @param int $parameterKey
     * @return string
     */
    protected function addOptionsValues($swetestOption, array $parameterValuesData, \Illuminate\Config\Repository $optionsValues, int $parameterKey): string
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
