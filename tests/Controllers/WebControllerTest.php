<?php

namespace Yoeunes\Larafast\Tests\Controllers;

use Illuminate\Foundation\Testing\TestResponse;
use Yoeunes\Larafast\Tests\TestCase;

class WebControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->resource('lessons', 'Yoeunes\Larafast\Tests\Stubs\Controllers\Web\LessonController');
    }

    /** @test */
    public function it_show_create_page()
    {
        /** @var TestResponse $response */
        $response = $this->call('get', '/lessons/create');

        $response->assertSuccessful();
        $response->assertSee('<title>lessons create |  Larafast</title>');
        $response->assertSee('<i class="fa fa-plus-circle"></i> lessons create');
        $response->assertSee('<form method="POST" action="http://localhost/lessons" accept-charset="UTF-8" enctype="multipart/form-data">');
    }
}
