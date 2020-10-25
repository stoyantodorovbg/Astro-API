<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;

class ValidationServiceTest extends TestCase
{
    /** @test */
    public function contains_exact_value_method_chechs_if_the_serched_value_is_presented()
    {
        $validationService = resolve(ValidationServiceInterface::class);
        $generateCommandService = resolve(GenerateCommandServiceInterface::class);

        $this->assertTrue($validationService->containsExactValue(
            $generateCommandService,
            'swetest',
            'acceptableCommands'
        ));

        $this->assertFalse($validationService->containsExactValue(
            $generateCommandService,
            'swe-test',
            'acceptableCommands'
        ));
    }

    /** @test */
    public function contains_exact_values_method_chechs_if_the_serched_values_are_presented()
    {
        $validationService = resolve(ValidationServiceInterface::class);
        $generateCommandService = resolve(GenerateCommandServiceInterface::class);

        $this->assertTrue($validationService->containsExactValues($generateCommandService,
            [
                'acceptableOptions' => ['bj']
            ]
        ));

        $this->assertFalse($validationService->containsExactValues(
            $generateCommandService,
            [
                'acceptableOptions' => ['bju']
            ]
        ));
    }
}
