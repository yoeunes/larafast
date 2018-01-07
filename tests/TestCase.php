<?php

namespace Yoeunes\Larafast\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Schema;
use Laracasts\TestDummy\Factory;
use Mockery;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yoeunes\Larafast\LarafastServiceProvider;
use Yoeunes\Larafast\Tests\Stubs\Entities\User;
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
        Schema::drop('role_has_permissions');
        Schema::drop('model_has_roles');
        Schema::drop('model_has_permissions');
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('users');
        Mockery::close();
    }

    /** @var User $adminUser */
    protected $adminUser;

    /** @var User $normalUser */
    protected $normalUser;

    public function setUp()
    {
        parent::setUp();

        $adminRole = Factory::create(Role::class, ['name' => 'admin']);
        $this->adminUser = Factory::create(User::class, ['name' => 'admin']);
        $this->adminUser->assignRole($adminRole);

        $normalRole = Factory::create(Role::class, ['name' => 'normal']);
        $this->normalUser = Factory::create(User::class, ['name' => 'normal']);
        $this->normalUser->assignRole($normalRole);

        $view = Factory::create(Permission::class, ['name' => 'lessons view']);
        $create = Factory::create(Permission::class, ['name' => 'lessons create']);
        $update = Factory::create(Permission::class, ['name' => 'lessons update']);
        $delete = Factory::create(Permission::class, ['name' => 'lessons delete']);

        $adminRole->givePermissionTo([$view, $create, $update, $delete]);
        $normalRole->givePermissionTo([$view, $create]);
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

        $app['config']->set('auth', [
            'defaults' => [
                'guard'     => 'web',
                'passwords' => 'users',
            ],
            'guards' => [
                'web' => [
                    'driver'   => 'session',
                    'provider' => 'users',
                ],

                'api' => [
                    'driver'   => 'token',
                    'provider' => 'users',
                ],
            ],
            'providers'    => [
                'users' => [
                    'driver' => 'eloquent',
                    'model'  => \Yoeunes\Larafast\Tests\Stubs\Entities\User::class,
                ],
            ],
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
            $table->boolean('active')->default(false);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->morphs('model');

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->morphs('model');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->integer('permission_id')->unsigned();
            $table->integer('role_id')->unsigned();

            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);

            app('cache')->forget('spatie.permission.cache');
        });

        $kernel = app('Illuminate\Contracts\Http\Kernel');

        $kernel->pushMiddleware(\Illuminate\Session\Middleware\StartSession::class);
    }

    protected function showResponse(TestResponse $response)
    {
        dump($response->content());
        dd($response->exception ? $response->exception->getMessage() : null);
    }
}
