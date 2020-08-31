<?php

namespace amirniknami\LaravelLogger\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use amirniknami\LaravelLogger\App\Http\Traits\ActivityLogger;

class LogWorkerActivity
{
    use ActivityLogger;

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $description = null)
    {
           $isRegular = is_null($request->user) ? true : $request->user()->isRegularUser();
        if (!$isRegular) {
            ActivityLogger::activity($description);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should log.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldLog($request)
    {
        foreach (config('LaravelLogger.loggerMiddlewareExcept', []) as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return false;
            }
        }

        return true;
    }
}
