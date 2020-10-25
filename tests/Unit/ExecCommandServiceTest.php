<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Interfaces\ExecCommandServiceInterface;

class ExecCommandServiceTest extends TestCase
{
    /** @test */
    public function exec_command_returns_an_empty_array_when_the_command_is_not_found()
    {
        $execCommandService = resolve(ExecCommandServiceInterface::class);

        $this->assertSame($execCommandService->exec('tcdecde'), []);
    }

    /** @test */
    public function exec_command_executes_a_command_by_given_string_and_returns_the_result()
    {
        $execCommandService = resolve(ExecCommandServiceInterface::class);

        $this->assertSame($execCommandService->exec('pwd'), [getcwd()]);
    }
}
