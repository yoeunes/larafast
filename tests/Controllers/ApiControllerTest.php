<?php

namespace Yoeunes\Larafast\Tests\Controllers;

use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\TestResponse;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

class ApiControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        auth()->login($this->adminUser);

        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->resource('lessons', 'Yoeunes\Larafast\Tests\Stubs\Controllers\Api\LessonController');
    }

    /** @test */
    public function it_return_pagination_with_api_response()
    {
        Factory::times(100)->create(Lesson::class);

        /** @var TestResponse $response */
        $response = $this->getJson('/lessons');

        $response->assertSuccessful();

        $response->assertJsonCount(15, 'data');
        $response->assertJsonFragment(['total' => 100]);
    }

    /** @test */
    public function it_return_response_not_found_when_lessons_table_empty()
    {
        /** @var TestResponse $result */
        $response = $this->getJson('/lessons');

        $this->assertTrue($response->isNotFound());
        $response->assertJsonFragment(['message' => 'Not Found']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_NOT_FOUND]);
    }

    /** @test */
    public function it_return_lesson_with_id()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->getJson('/lessons/'.$lesson->id);

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'id'      => $lesson->id,
            'title'   => $lesson->title,
            'subject' => $lesson->subject,
        ]);
    }

    /** @test */
    public function it_return_response_not_found_when_lesson_not_found()
    {
        /** @var TestResponse $result */
        $response = $this->getJson('/lessons/9');

        $this->assertTrue($response->isNotFound());
        $response->assertJsonFragment(['message' => 'Not Found']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_NOT_FOUND]);
    }

    /** @test */
    public function it_create_a_new_record_in_lessons_table()
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
        $response = $this->putJson('/lessons/9', $data);

        $this->assertTrue($response->isNotFound());
        $response->assertJsonFragment(['message' => 'Not Found']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_NOT_FOUND]);
    }

    /** @test */
    public function it_return_update_validation_errors()
    {
        $lesson = Factory::create(Lesson::class);

        /** @var TestResponse $result */
        $response = $this->putJson('/lessons/'.$lesson->id);

        $expected = ['title' => ['The title field is required.'], 'subject' => ['The subject field is required.']];
        $response->assertJsonFragment($expected);
        $response->assertJsonFragment(['message' => 'The given data was invalid.']);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /** @test */
    public function it_delete_lesson_from_database()
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
        $response = $this->deleteJson('/lessons/9');

        $this->assertTrue($response->isNotFound());
        $response->assertJsonFragment(['message' => 'Not Found']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_NOT_FOUND]);
    }

    /** @test */
    public function it_deny_normal_user_from_updating_a_lesson()
    {
        $lesson = Factory::create(Lesson::class);

        $data = ['title' => 'new title', 'subject' => 'new subject'];

        auth()->login($this->normalUser);

        /** @var TestResponse $result */
        $response = $this->putJson('/lessons/'.$lesson->id, $data);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonFragment(['message' => 'Unauthorized']);
        $response->assertJsonFragment(['status_code' => Response::HTTP_UNAUTHORIZED]);
    }

    /** @test */
    public function it_allow_normal_user_for_whitelist_routes()
    {
        $lesson = Factory::create(Lesson::class);

        auth()->login($this->normalUser);

        /** @var TestResponse $result */
        $response = $this->deleteJson('/lessons/'.$lesson->id);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing(Lesson::TABLE, ['id' => $lesson->id]);
    }
}
