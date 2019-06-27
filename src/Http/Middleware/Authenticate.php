<?php

namespace Everalan\Api\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guard)
    {
        if(!Auth::check())
        {
            throw new HttpException(401,'Unauthenticated.');
        }
        return $next($request);
    }
}
