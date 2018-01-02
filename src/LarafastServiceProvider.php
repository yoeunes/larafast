<?php

namespace Yoeunes\Larafast;

use Illuminate\Support\ServiceProvider;

class LarafastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/config/larafast.php', 'larafast');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'larafast');

        $this->publishes([
            __DIR__.'/config/larafast.php' => config_path('larafast.php'),
            __DIR__.'/views'               => resource_path('views/'.config('larafast.views_path')),
            __DIR__.'/macros'              => resource_path('macros/'.config('larafast.macros_path')),
            __DIR__.'/assets'              => public_path(config('larafast.assets_path')),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerAliases();
        $this->registerMiddlewares();
    }

    private function registerServices()
    {
        if ('production' !== $this->app->environment()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->register(\Barryvdh\Cors\ServiceProvider::class);
        $this->app->register(\Laravel\Passport\PassportServiceProvider::class);
        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Kamaln7\Toastr\ToastrServiceProvider::class);
        $this->app->register(\Spatie\Fractal\FractalServiceProvider::class);
        $this->app->register(\Spatie\MediaLibrary\MediaLibraryServiceProvider::class);
        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
        $this->app->register(\Yajra\DataTables\DataTablesServiceProvider::class);
    }

    private function registerAliases()
    {
        if (class_exists(\Illuminate\Foundation\AliasLoader::class)) {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Form', \Collective\Html\FormFacade::class);
            $loader->alias('Html', \Collective\Html\HtmlFacade::class);
            $loader->alias('Fractal', \Spatie\Fractal\FractalFacade::class);
            $loader->alias('Toastr', \Kamaln7\Toastr\Facades\Toastr::class);
        }
    }

    private function registerMiddlewares()
    {
        $this->app['router']->pushMiddlewareToGroup('web', \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Yoeunes\Larafast\Middlewares\BlacklistRoutes::class);
        $this->app['router']->pushMiddlewareToGroup('web', \Yoeunes\Larafast\Middlewares\UriSessionForWebRoutes::class);
        $this->app['router']->pushMiddlewareToGroup('api', \Barryvdh\Cors\HandleCors::class);
    }
}
