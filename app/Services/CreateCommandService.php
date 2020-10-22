<?php

namespace App\Services;

use App\Services\Interfaces\CreateCommandServiceInterface;

class CreateCommandService implements CreateCommandServiceInterface
{

    public function createCommand(string $command, array $params = [], array $flags = []): string
    {
        // TODO: Implement createCommand() method.
    }
}
