<?php

namespace App\Services;

use App\Services\Interfaces\ExecCommandServiceInterface;

class ExecCommandService implements ExecCommandServiceInterface
{

    public function exec(string $command): array
    {
        exec($command, $output);

        return $output;
    }
}
