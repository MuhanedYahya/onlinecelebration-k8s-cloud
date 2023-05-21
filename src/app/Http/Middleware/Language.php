<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

// Metric requirements
use Illuminate\Support\Facades\DB;
use App\Prometheus\Prom;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

class Language
{

    public function handle(Request $request, Closure $next)
    {

        // enable database query log

        // get Route name , HTTP method and HTTP status code
        $response = $next($request);
        $routeName = $request->route()->getName();
        $method = $request->method();
        $statusCode = $response->getStatusCode();

        // (number of HTTP requests) counter
        $counter = Prom::getOrRegisterCounter(
            'total',
            'http_requests',
            'Total number of HTTP requests by route, method, and status code',
            ['route', 'method', 'http_status_code']
        );
        // increment counter based on Route name , HTTP method and HTTP status code
        $counter->inc(['route' =>$routeName, 'method' => $method, 'http_status_code' => $statusCode]);


        // Duration of HTTP requests Histogram
        $histogram = Prom::getOrRegisterHistogram('http', 'request_duration_seconds',
        'Duration of HTTP requests by method and status code',
        ['method', 'http_status_code']);

        $start = microtime(true);
        $duration = microtime(true) - $start;
        $histogram->observe(
            $duration,
            ['method' => $method, 'http_status_code' => $statusCode]
        );

        // enable database query log
        DB::connection()->enableQueryLog();

        // Application codes
        if (Session()->has('applocale') AND array_key_exists(Session()->get('applocale'), config('languages'))) {
            App::setLocale(Session()->get('applocale'));
        }
        else { // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }

    // Metric requirements method
    public function terminate($request, $response)
    {
        // get Route name , HTTP method and HTTP status code
        $routeName = Route::currentRouteName();
        $method = $request->method();
        $statusCode = $response->status();

        // Retrieve the executed queries from the query log
        DB::connection()->disableQueryLog();
        $queries = DB::getQueryLog();

        // Count the number of queries executed
        $queryCount = count($queries);
        $Qcounter = Prom::getOrRegisterCounter(
            'database',
            'query_count',
            'Total number of database queries executed',
            ['method', 'http_status_code']
        );
        $Qcounter->incBy($queryCount,['method' => $method, 'http_status_code' => $statusCode]);

        // Calculate the total query execution time
        $totalExecutionTime = array_sum(array_column($queries, 'time'));
        // Calculate the average query execution time
        $averageExecutionTime = $totalExecutionTime / max($queryCount, 1);
        // query_count
        $histogram = Prom::getOrRegisterHistogram(
            'database',
            'query_execution_time_seconds',
            'Database query execution time in seconds',
            ['type','method']
        );
        $histogram->observe($averageExecutionTime, ['type' => 'average','method' => $method]);
        foreach ($queries as $query) {
            if ($query['time'] > 1.0) {
                $histogram->observe($query['time'], ['type' => 'slow',$method, 'http_status_code']);
            }
        }

    }
}
