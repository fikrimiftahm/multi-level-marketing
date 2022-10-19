<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\ApigeeAuthenticationRepositoryInterface;
use App\Repository\Eloquent\ApigeeAuthenticationRepository;
use App\Repository\Eloquent\MemberRepository;
use App\Repository\MemberRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApigeeAuthenticationRepositoryInterface::class, ApigeeAuthenticationRepository::class);
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
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
