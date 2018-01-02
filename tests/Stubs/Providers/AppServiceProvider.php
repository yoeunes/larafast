<?php

namespace Yoeunes\Larafast\Tests\Stubs\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMacros();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function bootMacros()
    {
        require __DIR__.'/../../../src/macros/blade.php';
        require __DIR__.'/../../../src/macros/components.php';
        require __DIR__.'/../../../src/macros/routes.php';
    }
}
