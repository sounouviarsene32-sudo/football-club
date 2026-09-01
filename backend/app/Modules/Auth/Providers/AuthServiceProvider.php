<?php

namespace App\Modules\Auth\Providers;

use App\Modules\Auth\Repositories\AuthRepository;
use App\Modules\Auth\Repositories\AuthRepositoryInterface;
use App\Modules\Auth\Services\AuthService;
use App\Modules\Auth\Services\AuthServiceInterface;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer les bindings Repository
        $this->app->bind(
            AuthRepositoryInterface::class,
            AuthRepository::class
        );

        // Enregistrer les bindings Service
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
