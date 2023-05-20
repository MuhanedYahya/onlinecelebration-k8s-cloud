<?php

namespace App\Prometheus;


use Prometheus\CollectorRegistry;
use Illuminate\Support\Facades\Facade;

class Prom extends Facade
{
    public static function fake()
    {
        static::swap(new Fake);
    }

    protected static function getFacadeAccessor()
    {
        return CollectorRegistry::class;
    }
}
