<?php

namespace Yoeunes\Larafast\Tests\Controllers;

use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\TestResponse;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

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

        $response->assertSuccessful();

        $response->assertSee('<title>lessons index |  Larafast</title>');
        $response->assertSee('<table  class="table table-bordered" id="dataTableBuilder"><thead><tr><th >Id</th><th >Title</th><th >Subject</th><th >Active</th><th >User Id</th><th >Created At</th><th >Updated At</th><th  width="80px">Action</th></tr></thead></table>');
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

        $this->assertTrue($response->isNotFound());
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

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseHas(Lesson::TABLE, $data);
    }

    /** @test */
    public function it_store_data_to_database_and_return_json_response()
    {
        $data = ['title' => 'laravel test store method', 'subject' => 'php'];

        /** @var TestResponse $result */
        $response = $this->postJson('/lessons', $data);

        $response->assertSuccessful();
        $response->assertJsonFragment(['message' => 'Created']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_CREATED]);
        $response->assertJsonFragment($data);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas(Lesson::TABLE, $data);
    }

    /** @test */
    public function it_return_store_validation_errors()
    {
        /** @var TestResponse $result */
        $response = $this->post('/lessons');

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');

        $this->assertInstanceOf(\Illuminate\Validation\ValidationException::class, $response->exception);
        $this->assertEquals('The given data was invalid.', $response->exception->getMessage());

        /** @var \Illuminate\Validation\ValidationException $exception */
        $exception = $response->exception;
        $expected  = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $this->assertEquals($expected, $exception->errors());
    }

    /** @test */
    public function it_return_store_validation_errors_in_json_format()
    {
        /** @var TestResponse $result */
        $response = $this->postJson('/lessons');

        $expected = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $response->assertJsonFragment($expected);
        $response->assertJsonFragment(['message' => 'The given data was invalid.']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_update_lesson_in_database()
    {
        $lesson = Factory::create(Lesson::class);

        $data = ['title' => 'new title', 'subject' => 'new subject'];

        /** @var TestResponse $result */
        $response = $this->put('/lessons/'.$lesson->id, $data);

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseHas(Lesson::TABLE, array_merge(['id' => $lesson->id], $data));
    }

    /** @test */
    public function it_update_lesson_in_database_and_return_json_response()
    {
        $lesson = Factory::create(Lesson::class);

        $data = ['title' => 'new title', 'subject' => 'new subject'];

        /** @var TestResponse $result */
        $response = $this->putJson('/lessons/'.$lesson->id, $data);

        $response->assertSuccessful();
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonFragment(['message' => 'Accepted']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_ACCEPTED]);
        $this->assertDatabaseHas(Lesson::TABLE, array_merge(['id' => $lesson->id], $data));
    }

    /** @test */
    public function it_return_404_when_lesson_not_exists()
    {
        $data = ['title' => 'new title', 'subject' => 'new subject'];

        /** @var TestResponse $result */
        $response = $this->put('/lessons/9', $data);

        $this->assertTrue($response->isNotFound());
        $response->assertSeeText('Sorry, the page you are looking for could not be found.');
    }

    /** @test */
    public function it_return_update_validation_errors()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->put('/lessons/'.$lesson->id);

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');

        $this->assertInstanceOf(\Illuminate\Validation\ValidationException::class, $response->exception);
        $this->assertEquals('The given data was invalid.', $response->exception->getMessage());

        /** @var \Illuminate\Validation\ValidationException $exception */
        $exception = $response->exception;
        $expected  = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $this->assertEquals($expected, $exception->errors());
    }

    /** @test */
    public function it_delete_lesson_from_database()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->delete('/lessons/'.$lesson->id);

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseMissing(Lesson::TABLE, ['id' => $lesson->id]);
    }

    /** @test */
    public function it_delete_lesson_from_database_and_return_json_response()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->deleteJson('/lessons/'.$lesson->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing(Lesson::TABLE, ['id' => $lesson->id]);
    }

    /** @test */
    public function it_return_not_found_if_trying_to_delete_non_existing_lesson()
    {
        /** @var TestResponse $result */
        $response = $this->delete('/lessons/9');

        $this->assertTrue($response->isNotFound());
        $response->assertSeeText('Sorry, the page you are looking for could not be found.');
    }

//    /** @test */
//    public function it_deny_normal_user_from_updating_a_lesson()
//    {
//        $lesson = Factory::create(Lesson::class);
//
//        $data = ['title' => 'new title', 'subject' => 'new subject'];
//
//        auth()->login($this->normalUser);
//
//        /** @var TestResponse $result */
//        $response = $this->put('/lessons/'.$lesson->id, $data);
//
//        $this->assertInstanceOf(\Illuminate\Auth\Access\AuthorizationException::class, $response->exception);
//        $this->assertEquals('This action is unauthorized.', $response->exception->getMessage());
//        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->exception->getCode());
//    }

    /** @test */
    public function it_allow_normal_user_for_whitelist_routes()
    {
        $lesson = Factory::create(Lesson::class);

        auth()->login($this->normalUser);

        /** @var TestResponse $result */
        $response = $this->delete('/lessons/'.$lesson->id);

        $this->assertTrue($response->isRedirection());
        $response->assertSee('<title>Redirecting to http://localhost</title>');
        $response->assertSee('Redirecting to <a href="http://localhost">http://localhost</a>.');
        $this->assertDatabaseMissing(Lesson::TABLE, ['id' => $lesson->id]);
    }
}
