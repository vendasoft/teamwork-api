<?php

namespace Teamwork\Providers;

use Teamwork\Factories\TeamworkApiServiceFactory;
use Illuminate\Support\ServiceProvider;
use Teamwork\Interfaces\TeamworkApiInterface;
use Teamwork\Services\TeamworkApiService;

class TeamworkApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TeamworkApiInterface::class, function (): TeamworkApiService {
            return TeamworkApiServiceFactory::create();
        });
    }
}