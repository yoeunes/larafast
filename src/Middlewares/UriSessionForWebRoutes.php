<?php

namespace Yoeunes\Larafast\Middlewares;

use Closure;
use Yoeunes\Larafast\Controllers\WebController;

class UriSessionForWebRoutes
{
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
        /** @var \Illuminate\Routing\Route$route */
        if (null !== ($route = app('router')->getCurrentRoute())
            && is_a($controller = $route->getController(), WebController::class)) {
            session(['uri' => $route->getPrefix().'.'.$route->getName()]);
        }

        return $next($request);
    }
}
