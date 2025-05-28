<?php

use Factories\TeamworkApiServiceFactory;
use Illuminate\Support\ServiceProvider;
use Interfaces\TeamworkApiInterface;
use Services\TeamworkApiService;

class TeamworkApiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TeamworkApiInterface::class, function (): TeamworkApiService {
            return TeamworkApiServiceFactory::create();
        });
    }
}