<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\HeliacalEventRepository;
use App\Repositories\Interfaces\HeliacalEventRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(HeliacalEventRepositoryInterface::class, HeliacalEventRepository::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
