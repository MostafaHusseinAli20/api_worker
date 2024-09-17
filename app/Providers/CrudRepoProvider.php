<?php

namespace App\Providers;

use App\Interfaces\Client\CrudRepoInterface;
use App\Repository\ClientOrderRepo;
use Illuminate\Support\ServiceProvider;

class CrudRepoProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CrudRepoInterface::class, ClientOrderRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
