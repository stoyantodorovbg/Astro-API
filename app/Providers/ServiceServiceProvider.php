<?php

namespace App\Providers;

use App\Services\ConvertDateService;
use App\Services\GenerateCommandService;
use App\Services\ExecCommandService;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;
use App\Services\Interfaces\ExecCommandServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\ValidationService;
use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ConvertDateServiceInterface::class, ConvertDateService::class);
        $this->app->bind(GenerateCommandServiceInterface::class, GenerateCommandService::class);
        $this->app->bind(ExecCommandServiceInterface::class, ExecCommandService::class);
        $this->app->bind(ConvertDateServiceInterface::class, ConvertDateService::class);
        $this->app->bind(ValidationServiceInterface::class, ValidationService::class);
    }
}
