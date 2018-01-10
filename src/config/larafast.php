<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    */

    'path' => [
        'routes'                    => base_path('routes/web.php'),
        'api_routes'                => base_path('routes/api.php'),
        'migration'                 => database_path('migrations/'),
        'entity'                    => app_path('Entities/'),
        'datatables'                => app_path('DataTables/'),
        'datatables_scope'          => app_path('DataTables/Scopes/'),
        'datatables_default_action' => 'layout/partials/_datatables_action/',
        'job'                       => app_path('Jobs/'),
        'policy'                    => app_path('Policies/'),
        'web_controller'            => app_path('Http/Controllers/Web/'),
        'api_controller'            => app_path('Http/Controllers/Api/'),
        'transformer'               => app_path('Transformers/'),
        'service'                   => app_path('Services/'),
        'views'                     => 'default/',
        'lang'                      => resource_path('lang/'),
        'default_form'              => 'default/_form',
        'assets'                    => 'assets/',
        'macros'                    => resource_path('macros/'),
        'components'                => 'components/',
        'translations'              => resource_path('lang/vendor/larafast/'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => [
        'entity'            => 'App\Entities',
        'datatables'        => 'App\DataTables',
        'datatables_scope'  => 'App\DataTables\Scopes',
        'web_controller'    => 'App\Http\Controllers\Web',
        'api_controller'    => 'App\Http\Controllers\Api',
        'policy'            => 'App\Policies',
        'transformer'       => 'App\Transformers',
        'service'           => 'App\Services',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exceptions
    |--------------------------------------------------------------------------
    |
    */

    'exception'                   => [
        \Illuminate\Auth\Access\AuthorizationException::class       => ['method' => 'unauthorized', 'message' => null],
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => ['method' => 'notFound', 'message' => null],
        \Illuminate\Auth\AuthenticationException::class             => ['method'  => 'unauthorized', 'message' => 'Unauthenticated'],
    ],

];
