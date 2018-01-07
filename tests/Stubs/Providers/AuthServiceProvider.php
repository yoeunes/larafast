<?php

namespace Yoeunes\Larafast\Tests\Stubs\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;
use Yoeunes\Larafast\Tests\Stubs\Policies\LessonPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
