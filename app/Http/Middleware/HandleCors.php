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
        $request = parent::handle($request, $next);
        \Log::info($request->headers);
        return $request;
    }
}
