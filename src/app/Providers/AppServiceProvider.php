<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RequestRepositoryInterface;
use App\Repositories\RequestRepository;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

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
        // 管理者のみを許可するGate定義
        Gate::define('admin-only', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Paginator::useTailwind();
    }
}
