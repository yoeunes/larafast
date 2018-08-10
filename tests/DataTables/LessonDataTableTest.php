<?php

namespace Yoeunes\Larafast\Tests\DataTables;

use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\Stubs\DataTables\LessonDataTable;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;
use Yoeunes\Larafast\Tests\TestCase;

class LessonDataTableTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Factory::times(10)->create(Lesson::class);
    }

    /** @test */
    public function it_get_the_correct_sql_select_query()
    {
        $datatables = new LessonDataTable();

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $datatables->query(new Lesson());

        $this->assertEquals('select "id", "title", "subject", "active", "user_id", "created_at", "updated_at" from "lessons"', $query->toSql());
        $this->assertCount(10, $query->get());
    }

    /** @test */
    public function it_get_selected_attributes()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            'title',
            'subject'
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $datatables->query(new Lesson());

        $this->assertEquals('select "id", "title", "subject" from "lessons"', $query->toSql());
        $this->assertCount(10, $query->get());
    }

    /** @test */
    public function it_get_eager_load_a_user()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            'title',
            'subject',
            'user.id'
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $datatables->query(new Lesson());
        $this->assertCount(10, $query->get());
    }

    /** @test */
    public function it_rename_selected_attributes()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            ['name' => 'title', 'title' => 'Title', 'data' => 'title'],
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $query */
        $query = $datatables->query(new Lesson());
        $this->assertCount(10, $query->get());
    }
}
