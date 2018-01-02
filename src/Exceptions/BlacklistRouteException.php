<?php

namespace Yoeunes\Larafast\Exceptions;

use Exception;
use Illuminate\Http\Request;

class BlacklistRouteException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return response()->view('errors.404');
    }
}
