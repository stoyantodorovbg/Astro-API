<?php

namespace App\Providers;

use App\Services\FormatDataService;
use App\Services\ValidationService;
use App\Services\ExtractDataService;
use App\Services\ConvertDateService;
use App\Services\ExecCommandService;
use App\Services\CalculateDataService;
use App\Services\HttpConnectorService;
use Illuminate\Support\ServiceProvider;
use App\Services\GenerateCommandService;
use App\Services\Interfaces\FormatDataServiceInterface;
use App\Services\Interfaces\ValidationServiceInterface;
use App\Services\Interfaces\ExtractDataServiceInterface;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\ExecCommandServiceInterface;
use App\Services\Interfaces\CalculateDataServiceInterface;
use App\Services\Interfaces\HttpConnectorServiceInterface;
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
        $this->app->bind(HttpConnectorServiceInterface::class, HttpConnectorService::class);
        $this->app->bind(CalculateDataServiceInterface::class, CalculateDataService::class);
        $this->app->bind(ExtractDataServiceInterface::class, ExtractDataService::class);
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
