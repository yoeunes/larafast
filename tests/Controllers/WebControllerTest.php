<?php

namespace Yoeunes\Larafast\Tests\Controllers;

use Illuminate\Foundation\Testing\TestResponse;
use Yoeunes\Larafast\Controllers\WebController;
use Yoeunes\Larafast\Tests\Stubs\Controllers\Web\LessonController;
use Yoeunes\Larafast\Tests\TestCase;

class WebControllerTest extends TestCase
{
    /** @var WebController */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = new LessonController();

        $this->app['router']->resource('/lesson', 'Yoeunes\Larafast\Tests\Stubs\Controllers\Web\LessonController');
    }

    /** @test */
    public function it_show_create_page()
    {
        /** @var TestResponse $response */
        $response = $this->get('/lesson/create');
dd($response);
        $response->assertSuccessful();
        $response->assertSeeText('welcome to abstract show page');
    }
}
