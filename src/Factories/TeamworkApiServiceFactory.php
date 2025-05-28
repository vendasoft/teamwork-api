<?php

namespace Factories;

use Services\TeamworkApiService;
use Illuminate\Support\Facades\Config;

class TeamworkApiServiceFactory
{
    public static function create(): TeamworkApiService
    {
        return new TeamworkApiService(
            siteUrl: Config::get('teamwork.site_url'),
            username: Config::get('teamwork.site_url'),
            password: Config::get('teamwork.site_url'),
        );
    }
}