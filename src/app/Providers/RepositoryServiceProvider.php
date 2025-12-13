<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Contracts\GameLinkRepositoryInterface;
use App\Repositories\Eloquent\GameLinkRepository;
use App\Repositories\Contracts\GameResultRepositoryInterface;
use App\Repositories\Eloquent\GameResultRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(GameLinkRepositoryInterface::class, GameLinkRepository::class);
        $this->app->bind(GameResultRepositoryInterface::class, GameResultRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
