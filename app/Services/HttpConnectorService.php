<?php

namespace App\Services;

use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\HttpConnectorServiceInterface;

class HttpConnectorService implements HttpConnectorServiceInterface
{
    /**
     * Connects HTTP parameters to Swetest options and validated theirs values
     * Returns an array with options for Swetest command
     * Returns an array with key "error" when something when wrong
     *
     * @param array $options
     * @return array
     */
    public function connectSwetestOptions(array $options): array
    {
        $connectedOptions = [];
        $optionsKeys = config('swetest.httpMapping.optionsKeys');
        $validationsOptions = config('swetest.validations.options');
        $optionsValues = config('swetest.httpMapping.optionsValues');

        foreach ($options as $parameterKey => $parameterValues) {
            // Check for Swetest parameters
            if (array_key_exists($parameterKey, $optionsKeys) &&
                ($swetestOption = $optionsKeys[$parameterKey]) &&
                array_key_exists($swetestOption, $validationsOptions)
            ) {
                $parameterValuesData = explode(',', $parameterValues);
                $processedParameterValue = '';
                // Converts to Julian date
                if ($swetestOption === 'bj') {
                    $processedParameterValue = resolve(ConvertDateServiceInterface::class)->convertToJulianNumeric($parameterValues);
                }
                foreach ($parameterValuesData as $key => $parameterValue) {
                   // dump($parameterValue);
                    $dataValidationClasses = $validationsOptions[$swetestOption];

                    // Check if there is a validation for the option
                    foreach($dataValidationClasses as $dataValidationClass) {
                        if ($dataValidationClass && array_key_exists($dataValidationClass, $optionsKeys)) {
                            // Convert the HTTP option value to Swetest option value
                            $parameterOptionValues = explode('-', $parameterValue);
                            foreach ($parameterOptionValues as $parameterOptionValue) {
                                //dd($optionsKeys, $parameterOptionValue, $swetestOption);
                                $processedParameterValue .= $optionsValues[$parameterKey][$parameterValue];
                            }

                            // Validate Swetest option
                            $dataValidationClass = ucfirst($dataValidationClass) . 'Validation';
                            $dataValidation = resolve("\\App\\Services\\DataValidations\\$dataValidationClass");

                            if (!$dataValidation->isValid($swetestOption)) {
                                return [
                                    'error' => "$parameterKey HTTP option has not valid value."
                                ];
                            }
                        }
                    }
                }

                // Swetest option name => Swetest option value
                $connectedOptions[$swetestOption] = $processedParameterValue;
            }
        }

        return $connectedOptions;
    }
}
