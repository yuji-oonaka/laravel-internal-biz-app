<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RequestRepositoryInterface;
use App\Repositories\RequestRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Interface と 実装クラスを紐付け
        $this->app->bind(RequestRepositoryInterface::class, RequestRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
