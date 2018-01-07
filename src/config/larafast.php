<?php

return [
    'entities_namespace'           => 'App',
    'controllers_namespace'        => 'App\\Http\\Controllers',
    'datatable_namespace'          => 'App\\DataTables',
    'datatable_scope_namespace'    => 'App\\DataTables\\Scopes',
    'jobs_namespace'               => 'App\\Jobs',
    'gates_namespace'              => 'App\\Gates',
    'services_namespace'           => 'App\\Services',
    'transformers_namespace'       => 'App\\Transformers',
    'views_path'                   => 'larafast',
    'assets_path'                  => 'larafast',
    'macros_path'                  => 'larafast',
    'exceptions'                   => [
        \Illuminate\Auth\Access\AuthorizationException::class       => ['method' => 'unauthorized', 'message' => null],
        \Illuminate\Database\Eloquent\ModelNotFoundException::class => ['method' => 'notFound', 'message' => null],
        \Illuminate\Auth\AuthenticationException::class             => ['method'  => 'unauthorized', 'message' => 'Unauthenticated'],
    ],
];
