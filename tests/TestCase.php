<?php

namespace Yoeunes\Larafast\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Yoeunes\Larafast\LarafastServiceProvider;
use Yoeunes\Larafast\Tests\Stubs\Providers\AppServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            AppServiceProvider::class,
            LarafastServiceProvider::class,
        ];
    }

    public function tearDown()
    {
        Schema::drop('lessons');
        Mockery::close();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('app', [
            'name'   => 'Larafast',
            'locale' => 'en',
            'key'    => 'base64:O30Ogm4MaKjrqSAXq5okDox31Yt3MRn6eUjKymabybw=',
            'cipher' => 'AES-256-CBC',
        ]);

        $app['config']->set('view', [
            'paths'    => [__DIR__.'/Stubs/resources/views'],
            'compiled' => __DIR__.'/Stubs/storage/framework/views',
            'cache'    => false,
        ]);

        $app['config']->set('larafast', [
            'entities_namespace'     => 'Yoeunes\\Larafast\\Tests\\Stubs\\Entities',
            'controllers_namespace'  => 'Yoeunes\\Larafast\\Tests\\Stubs\\Controllers',
            'transformers_namespace' => 'Yoeunes\\Larafast\\Tests\\Stubs\\Transformers',
            'services_namespace'     => 'Yoeunes\\Larafast\\Tests\\Stubs\\Services',
            'policies_namespace'     => 'Yoeunes\\Larafast\\Tests\\Stubs\\Policies',
            'exceptions'             => [
                \Illuminate\Auth\Access\AuthorizationException::class       => ['method' => 'unauthorized', 'message' => null],
                \Illuminate\Database\Eloquent\ModelNotFoundException::class => ['method' => 'notFound', 'message' => null],
                \Illuminate\Auth\AuthenticationException::class             => ['method'  => 'unauthorized', 'message' => 'Unauthenticated'],
            ],
        ]);

        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject');
            $table->timestamps();
        });

        $kernel = app('Illuminate\Contracts\Http\Kernel');

        $kernel->pushMiddleware(\Illuminate\Session\Middleware\StartSession::class);
    }

    protected function showError(TestResponse $response)
    {
        dump($response->content());
        dd($response->exception ? $response->exception->getMessage() : null);
    }
}
