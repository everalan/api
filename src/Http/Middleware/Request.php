<?php

namespace Everalan\Api\Http\Middleware;

use Closure;
use Everalan\Api\Exception\Handler;
use Illuminate\Contracts\Container\Container;

class Request
{

    protected $app;
    protected $handler;
    public function __construct(Container $app, \Illuminate\Foundation\Exceptions\Handler $handler)
    {
        $this->app = $app;
        $this->handler = $handler;
    }
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );

        return $next($request);
    }
}
