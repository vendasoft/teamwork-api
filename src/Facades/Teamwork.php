<?php

namespace Teamwork\Facades;

use Illuminate\Support\Facades\Facade;
use Teamwork\Interfaces\TeamworkApiInterface;

class Teamwork extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TeamworkApiInterface::class;
    }
}