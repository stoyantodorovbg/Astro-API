<?php

namespace App\Providers;

use App\Services\ConvertDateService;
use App\Services\CreateCommandService;
use App\Services\ExecCommandService;
use App\Services\Interfaces\ConvertDateServiceInterface;
use App\Services\Interfaces\CreateCommandServiceInterface;
use App\Services\Interfaces\ExecCommandServiceInterface;
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
        $this->app->bind(CreateCommandServiceInterface::class, CreateCommandService::class);
        $this->app->bind(ExecCommandServiceInterface::class, ExecCommandService::class);
        $this->app->bind(ConvertDateServiceInterface::class, ConvertDateService::class);
    }
}
