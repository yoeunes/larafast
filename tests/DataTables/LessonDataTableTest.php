<?php

namespace Yoeunes\Larafast\Tests\DataTables;

use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\TestCase;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;
use Yoeunes\Larafast\Tests\Stubs\DataTables\LessonDataTable;

class LessonDataTableTest extends TestCase
{
    public const LESSONS_COUNT =  2;

    public function setUp()
    {
        parent::setUp();

        Factory::times(self::LESSONS_COUNT)->create(Lesson::class);
    }

    /** @test */
    public function it_get_the_correct_sql_select_query()
    {
        $datatables = new LessonDataTable();

        /** @var \Illuminate\Database\Eloquent\Builder $builder */
        $builder = $datatables->query(new Lesson());

        $this->assertEquals('select "id", "title", "subject", "active", "user_id", "created_at", "updated_at" from "lessons"', $builder->toSql());
        $this->assertCount(self::LESSONS_COUNT, $builder->get());
    }

    /** @test */
    public function it_get_selected_attributes()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            'title',
            'subject',
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $builder */
        $builder = $datatables->query(new Lesson());

        $this->assertEquals('select "id", "title", "subject" from "lessons"', $builder->toSql());
        $this->assertCount(self::LESSONS_COUNT, $builder->get());
    }

    /** @test */
    public function it_get_eager_load_a_user()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            'title',
            'subject',
            'user.id',
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $builder */
        $builder = $datatables->query(new Lesson());
        $this->assertCount(self::LESSONS_COUNT, $builder->get());
    }

    /** @test */
    public function it_rename_selected_attributes()
    {
        $datatables = new LessonDataTable();
        $datatables->setColumns([
            'id',
            ['name' => 'title', 'title' => 'Title', 'data' => 'title'],
        ]);

        /** @var \Illuminate\Database\Eloquent\Builder $builder */
        $builder = $datatables->query(new Lesson());
        $this->assertCount(self::LESSONS_COUNT, $builder->get());
    }

    /** @test */
    public function it_format_columns()
    {
        $datatables = new LessonDataTable();
        $datatables->render('default.index');
        $builder = $datatables->ajax()->getData(true);
    }
}
