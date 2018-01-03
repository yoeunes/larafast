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
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws BlacklistRouteException
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        dd(app('router')->getCurrentRoute());
        if (null !== ($route = app('router')->getCurrentRoute())
            && is_a($controller = $route->getController(), Controller::class)
            && in_array($route->getActionMethod(), $controller->getBlacklist(), true)) {
            throw new BlacklistRouteException();
        }

        return $next($request);
    }
}
