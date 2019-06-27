<?php
namespace Everalan\Api\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class UseGuard
{
    public function handle($request, Closure $next, ...$guard)
    {
        if($guard)
        {
            Auth::shouldUse($guard[0]);
        }

        return $next($request);
    }
}
