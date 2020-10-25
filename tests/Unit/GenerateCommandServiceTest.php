<?php

namespace Tests\Unit;

use App\Services\GenerateCommandService;
use Tests\TestCase;

class GenerateCommandServiceTest extends TestCase
{
    /** @test */
    public function generate_command_returns_correct_command_string_when_receives_valid_parameters()
    {
        $generateCommandService = resolve(GenerateCommandService::class);

        $command = $generateCommandService->generateCommand('swetest', [
            'p'  => 2,
            'bj' => 2415020.5232,
            'n'  => 15,
            's'  => 2,
        ]);

        $this->assertSame('swetest -p2 -bj2415020.5232 -n15 -s2', $command);
    }
}
