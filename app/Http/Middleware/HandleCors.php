<?php

namespace App\Http\Middleware;

use Closure;
use Fruitcake\Cors\HandleCors as Middleware;
use Symfony\Component\HttpFoundation\Response;

// FIXME: サンプルコードです。
class HandleCors extends Middleware
{
    public function handle($request, Closure $next): Response
    {
        \Log::info('HandleCors 1', [
            'headers', $request->headers,
            'shouldRun' => $this->shouldRun($request),
            'isPreflightRequest' => $this->cors->isPreflightRequest($request)
        ]);
        $response = parent::handle($request, $next);
        \Log::info('HandleCors 2', [
            'headers', $response->headers,
        ]);
        return $response;
    }
}
