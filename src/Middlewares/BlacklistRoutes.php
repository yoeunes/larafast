<?php

namespace Yoeunes\Larafast\Middlewares;

use Closure;
use Yoeunes\Larafast\Controllers\Controller;
use Yoeunes\Larafast\Exceptions\BlacklistRouteException;

class BlacklistRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws BlacklistRouteException
     */
    public function handle($request, Closure $next)
    {
        if (null !== ($route = app('router')->getCurrentRoute())
            && is_a($controller = $route->getController(), Controller::class)
            && in_array($route->getActionMethod(), $controller->getBlacklist(), true)) {
            throw new BlacklistRouteException;
        }

        return $next($request);
    }
}
