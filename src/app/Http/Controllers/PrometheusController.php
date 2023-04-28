<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Prometheus\RenderTextFormat;

class PrometheusController extends Controller
{
    public function index(Request $request){
        
       // Create a new instance of the CollectorRegistry class with the InMemory storage backend
       $registry = new CollectorRegistry(new \Prometheus\Storage\InMemory());

       // http_requests_total counter
       $counter = $registry->registerCounter('total','http_requests' ,'The total number of HTTP requests', ['method', 'endpoint']);
       $counter->inc([$request->getMethod(), 'endpoint' => '/']);

#######################################################################################################################
       // http_requests_duration
       $histogram = $registry->registerHistogram('http', 'request_duration_seconds', 'HTTP Request Duration in Seconds',['method', 'endpoint'], [0.05, 0.1, 0.5, 1, 2, 5,6,7,8,9,10]);
       $start = microtime(true);
       $end = microtime(true);
       $duration = $end - $start;       
       $histogram->observe($duration, [$request->getMethod(), 'endpoint' => '/']);
#######################################################################################################################
        // the total number of PHP errors
       $phpErrors = $registry->registerCounter('total', 'php_errors', 'Total number of PHP errors.');
        // Register an error handler to count PHP errors
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($phpErrors) {
        $phpErrors->inc();
        });
#######################################################################################################################   
        // the total number of database queries 
       $databaseQueries = $registry->registerCounter('database', 'queries_total', 'Total number of database queries.');
       // Count database queries
       \DB::listen(function ($query) use ($databaseQueries) {
           $databaseQueries->inc();
       });


       // Render the metrics in the Prometheus text format and return the response with the Content-Type header set to the text/plain MIME type
       $renderer = new RenderTextFormat();
       $result = $renderer->render($registry->getMetricFamilySamples());
       return response($result)->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
   
}