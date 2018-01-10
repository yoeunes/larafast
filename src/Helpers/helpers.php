<?php

if (! function_exists('active')) {
    /**
     * Sets the menu item class for an active route.
     *
     * @param $routes
     * @param $class
     * @param bool $condition
     *
     * @return string
     */
    function active($routes, string $class = 'active', bool $condition = true): string
    {
        return call_user_func_array([app('router'), 'is'], (array) $routes) && $condition ? ' '.$class : '';
    }
}

if (! function_exists('getForm')) {
    /**
     * @param string $action
     *
     * @return string
     */
    function getForm($action = 'create'): string
    {
        if (view()->exists($view = preg_replace('/(\.create|\.edit)/', '._form_'.$action, session('uri')))) {
            return $view;
        }

        if (view()->exists($view = preg_replace('/(\.create|\.edit)/', '._form', session('uri')))) {
            return $view;
        }

        return config('larafast.path.default_form.relative');
    }
}

if (! function_exists('success')) {
    /**
     * @param string      $message
     * @param string|null $title
     * @param array       $options
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    function success($message = '', string $title = null, array $options = [])
    {
        app('toastr')->success($message, $title, $options);

        return back();
    }
}

if (! function_exists('error')) {
    /**
     * @param string      $message
     * @param string|null $title
     * @param array       $options
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    function error($message = '', string $title = null, array $options = [])
    {
        app('toastr')->error($message, $title, $options);

        return back();
    }
}

if (! function_exists('information')) {
    /**
     * @param string      $message
     * @param string|null $title
     * @param array       $options
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    function information($message = '', string $title = null, array $options = [])
    {
        app('toastr')->info($message, $title, $options);

        return back();
    }
}

if (! function_exists('warning')) {
    /**
     * @param string      $message
     * @param string|null $title
     * @param array       $options
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    function warning($message = '', string $title = null, array $options = [])
    {
        app('toastr')->warning($message, $title, $options);

        return back();
    }
}

if (! function_exists('getExcelAttributesDefaultValue')) {
    /**
     * @param $default
     *
     * @return string
     */
    function getExcelAttributesDefaultValue($default)
    {
        switch ($default) {
            case 'Carbon::now()':
                return Carbon\Carbon::now()->toDateTimeString();
            default:
                return $default;
        }
    }
}
