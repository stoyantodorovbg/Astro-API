<?php

namespace App\Providers;

use App\Services\FormatDataService;
use App\Services\ValidationService;
use App\Services\ConvertDateService;
use App\Services\ExecCommandService;
use Illuminate\Support\ServiceProvider;
use App\Services\GenerateCommandService;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\ExecCommandServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ConvertDateServiceInterface::class, ConvertDateService::class);
        $this->app->bind(GenerateCommandServiceInterface::class, GenerateCommandService::class);
        $this->app->bind(ExecCommandServiceInterface::class, ExecCommandService::class);
        $this->app->bind(ConvertDateServiceInterface::class, ConvertDateService::class);
        $this->app->bind(ValidationServiceInterface::class, ValidationService::class);
        $this->app->bind(FormatDataServiceInterface::class, FormatDataService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
