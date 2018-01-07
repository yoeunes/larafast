<?php

namespace Yoeunes\Larafast\Tests\Controllers;

use Illuminate\Foundation\Testing\TestResponse;
use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;
use Yoeunes\Larafast\Tests\TestCase;

class WebControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        auth()->login($this->adminUser);

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->resource('lessons', 'Yoeunes\Larafast\Tests\Stubs\Controllers\Web\LessonController');
    }

    /** @test */
    public function it_show_index_page()
    {
        Factory::times(10)->create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->get('/lessons');
        $this->showResponse($response);
        $response->assertSuccessful();
        $response->assertSee('<title>lessons index |  Larafast</title>');
        $response->assertSee('<table  class="table table-bordered" id="dataTableBuilder"><thead><tr><th >Id</th><th >Title</th><th >Subject</th><th >Active</th><th >Created At</th><th >Updated At</th><th  width="80px">Action</th></tr></thead></table>');
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
        $response->assertSee('<i class="fa fa-arrow-circle-o-right"></i> Enregistrer');
    }

    /** @test */
    public function it_show_edit_page()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->get('/lessons/'.$lesson->id.'/edit');

        $response->assertSuccessful();
        $response->assertSee('<title>lessons edit |  Larafast</title>');
        $response->assertSee('<i class="fa fa-plus-circle"></i> lessons edit');
        $response->assertSee('<form method="POST" action="http://localhost/lessons/'.$lesson->id.'" accept-charset="UTF-8" enctype="multipart/form-data"><input name="_method" type="hidden" value="PUT">');
        $response->assertSee('<i class="fa fa-arrow-circle-o-right"></i> Enregistrer');
    }

    /** @test */
    public function it_show_page_not_found()
    {
        /** @var TestResponse $result */
        $response = $this->get('/lessons/9/edit');

        $response->isNotFound();
        $response->assertSee('<title>Page Not Found</title>');
        $response->assertSeeText('Sorry, the page you are looking for could not be found.');
    }

    /** @test */
    public function it_redirect_to_edit_page()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->get('/lessons/'.$lesson->id);

        $response->assertSee('Redirecting to <a href="http://localhost/lessons/');
        $response->assertSee('Redirecting to http://localhost/lessons/');
        $response->isRedirection();
    }

    /** @test */
    public function it_store_data_to_database()
    {
        $data = ['title' => 'laravel test store method', 'subject' => 'php'];

        /** @var TestResponse $result */
        $response = $this->post('/lessons', $data);
        $this->showResponse($response);
        $response->isRedirection();
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseHas(Lesson::TABLE, $data);
    }

    /** @test */
    public function it_return_store_validation_errors()
    {
        /** @var TestResponse $result */
        $response = $this->post('/lessons');

        $response->isRedirection();
        $response->assertSee('<title>Redirecting to http://localhost</title>');

        $this->assertInstanceOf(\Illuminate\Validation\ValidationException::class, $response->exception);
        $this->assertEquals('The given data was invalid.', $response->exception->getMessage());

        /** @var \Illuminate\Validation\ValidationException $exception */
        $exception = $response->exception;
        $expected = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $this->assertEquals($expected, $exception->errors());
    }

    /** @test */
    public function it_update_lesson_in_database()
    {
        $lesson = Factory::create(Lesson::class);

        $data = ['title' => 'new title', 'subject' => 'new subject'];

        /** @var TestResponse $result */
        $response = $this->put('/lessons/'.$lesson->id, $data);

        $response->isRedirection();
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseHas(Lesson::TABLE, array_merge(['id' => $lesson->id], $data));
    }

    /** @test */
    public function it_return_404_when_lesson_not_exists()
    {
        $data = ['title' => 'new title', 'subject' => 'new subject'];

        /** @var TestResponse $result */
        $response = $this->put('/lessons/9', $data);

        $response->isNotFound();
        $response->assertSeeText('Sorry, the page you are looking for could not be found.');
    }

    /** @test */
    public function it_return_update_validation_errors()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->put('/lessons/'.$lesson->id);

        $response->isRedirection();
        $response->assertSee('<title>Redirecting to http://localhost</title>');

        $this->assertInstanceOf(\Illuminate\Validation\ValidationException::class, $response->exception);
        $this->assertEquals('The given data was invalid.', $response->exception->getMessage());

        /** @var \Illuminate\Validation\ValidationException $exception */
        $exception = $response->exception;
        $expected = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $this->assertEquals($expected, $exception->errors());
    }

    /** @test */
    public function it_delete_lesson_from_database()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->delete('/lessons/'.$lesson->id);

        $response->isRedirection();
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseMissing(Lesson::TABLE, ['id' => $lesson->id]);
    }
}
