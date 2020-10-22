<?php

namespace App\Services\Interfaces;

interface CreateCommandServiceInterface
{
    /**
     * Create a command line by given command name, command parameters and command flags
     *
     * @param string $command
     * @param array $params
     * @param array $flags
     * @return string
     */
    public function createCommand(string $command, array $params = [], array $flags = []): string;
}
