<?php

namespace App\Services;

use App\Services\Interfaces\ConvertDateServiceInterface;
use Illuminate\Http\Request;
use App\Services\Interfaces\HttpConnectorServiceInterface;

class HttpConnectorService implements HttpConnectorServiceInterface
{
    /**
     * Connects HTTP parameters to Swetest options and validated theirs values
     * Returns an array with options for Swetest command
     * Returns ann array with key "error" when something when wrong
     *
     * @param Request $request
     * @return array
     */
    public function connectOptions(Request $request): array
    {
        $connectedOptions = [];
        $optionsKeys = config('swetest.httpMapping.optionsKeys');
        $validationsOptions = config('swetest.validations.options');

        foreach ($request->validated() as $parameterKey => $parameterValues) {
            // Check for Swetest parameters
            if (in_array($parameterKey, $optionsKeys, true) &&
                ($swetestOption = $optionsKeys[$parameterKey]) &&
                array_key_exists($swetestOption, $validationsOptions)
            ) {
                $parameterValuesData = explode(',', $parameterValues);
                $processedParameterValue = '';

                // Converts to Julian date
                if ($swetestOption === 'b') {
                    $processedParameterValue = resolve(ConvertDateServiceInterface::class)->convertToJulianNumeric($validationsOptions);
                }

                foreach ($parameterValuesData as $key => $parameterValue){
                    $dataValidationClass = $swetestOption[$key];

                    // Check if there is a validation for the option
                    if ($dataValidationClass && array_key_exists($dataValidationClass, $optionsKeys)) {

                        // Convert the HTTP option value to Swetest option value
                        $parameterOptionValues = explode('-', $parameterValue);
                        foreach ($parameterOptionValues as $parameterOptionValue) {
                            $processedParameterValue .= $optionsKeys[$parameterOptionValue];
                        }

                        // Validate Swetest option
                        $dataValidationClass = strtoupper($dataValidationClass);
                        $dataValidation = resolve("\\App\\Services\\DataValidations\\$dataValidationClass");

                        if (!$dataValidation->isValid($parameterValue)) {
                            return [
                                'error' => "$key HTTP option has not valid value. The error has been found in $dataValidationClass class."
                            ];
                        }

                        $processedParameterValue = $parameterValue;
                    }
                }

                // Swetest option name => Swetest option value
                $connectedOptions[$swetestOption] = $processedParameterValue;
            }
        }

        return $connectedOptions;
    }
}
