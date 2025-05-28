<?php

namespace Facades;

use Illuminate\Support\Facades\Facade;
use Interfaces\TeamworkApiInterface;

class Teamwork extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TeamworkApiInterface::class;
    }
}