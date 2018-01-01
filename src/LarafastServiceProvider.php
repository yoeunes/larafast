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

        $this->publishes([
            __DIR__.'/config/larafast.php' => config_path('larafast.php'),
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
    }

    private function registerServices()
    {
        if ('production' !== $this->app->environment()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
