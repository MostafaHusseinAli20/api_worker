<?php

namespace App\Providers;

use App\Interfaces\worker\WorkerReviewInterface;
use App\Repository\WorkerReviewRepo;
use Illuminate\Support\ServiceProvider;

class WorkerReviewProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(WorkerReviewInterface::class, WorkerReviewRepo::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
