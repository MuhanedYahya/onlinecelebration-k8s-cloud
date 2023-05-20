<?php

namespace App\Http\Controllers;

use App\Prometheus\Prom;
use Illuminate\Http\Request;
use Prometheus\RenderTextFormat;

class MetricsController extends Controller
{
    public function __invoke(Request $request)
    {
        $formatter = new RenderTextFormat;

        return response(
            $formatter->render(Prom::getMetricFamilySamples()),
            200,
            [
                'Content-Type' => RenderTextFormat::MIME_TYPE,
            ]
        );
    }
}
