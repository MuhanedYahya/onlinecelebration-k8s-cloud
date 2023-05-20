<?php

namespace App\Providers;

use Prometheus\Storage\Redis;
use Prometheus\CollectorRegistry;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CollectorRegistry::class, function() {
            Redis::setDefaultOptions(
                [
                    'host' => env('REDIS_HOST'),
                    'port' => env('REDIS_PORT'),
                    'password' => env('REDIS_PASSWORD'),
                    'timeout' => 0.1,
                    'read_timeout' => '10',
                    'persistent_connections' => false
                ]
            );

            return CollectorRegistry::getDefault();
        });
    }
}
