<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Services\Interfaces\ExecCommandServiceInterface;

class ExecCommandService implements ServiceInterface, ExecCommandServiceInterface
{

    /**
     * Exec a console command
     * Return the data from it
     *
     * @param string $command
     * @return array
     */
    public function exec(string $command): array
    {
        exec($command, $output);

        return $output;
    }
}
