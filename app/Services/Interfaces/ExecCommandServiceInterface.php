<?php

namespace App\Services\Interfaces;

interface ExecCommandServiceInterface
{
    /**
     * Exec a console command
     * Return the data from it
     *
     * @param string $command
     * @return array
     */
    public function exec(string $command): array;
}
