<?php

namespace Yoeunes\Larafast\Tests\Entities;

use Laracasts\TestDummy\Factory;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;
use Yoeunes\Larafast\Tests\TestCase;

class EntityTest extends TestCase
{
    /** @var Lesson $lesson */
    protected $lesson;

    public function setUp()
    {
        parent::setUp();

        $this->lesson = Factory::create(Lesson::class);
        $this->lesson->setRules(['title' => 'required|max:255', 'subject' => 'required|max:255']);
    }

    /** @test */
    public function it_return_rules_array()
    {
        $expected = ['title' => 'required|max:255', 'subject' => 'required|max:255'];
        $this->assertEquals($expected, $this->lesson->getRules());
    }

    /** @test */
    public function it_return_rules_as_store_rules()
    {
        $expected = ['title' => 'required|max:255', 'subject' => 'required|max:255'];
        $this->assertEquals($expected, $this->lesson->getRules('store'));
    }

    /** @test */
    public function it_return_rules_as_update_rules()
    {
        $expected = ['title' => 'required|max:255', 'subject' => 'required|max:255'];
        $this->assertEquals($expected, $this->lesson->getRules('store'));
    }

    /** @test */
    public function it_return_store_rules()
    {
        $this->lesson->setRules(['store' => ['title' => 'required']]);
        $this->assertEquals(['title' => 'required'], $this->lesson->getRules('store'));
    }

    /** @test */
    public function it_return_update_rules()
    {
        $this->lesson->setRules(['update' => ['title' => 'required']]);
        $this->assertEquals(['title' => 'required'], $this->lesson->getRules('update'));
    }

    /** @test */
    public function it_return_fillable_attribute()
    {
        $this->lesson->setFillableAttributes(['title', 'subject']);
        $this->assertEquals(['title', 'subject'], $this->lesson->getFillableAttributes());
    }

    /** @test */
    public function it_return_store_attributes()
    {
        $this->lesson->setFillableAttributes(['store' => ['title']]);
        $this->assertEquals(['title'], $this->lesson->getFillableAttributes('store'));
    }

    /** @test */
    public function it_return_update_attributes()
    {
        $this->lesson->setFillableAttributes(['update' => ['title']]);
        $this->assertEquals(['title'], $this->lesson->getFillableAttributes('update'));
    }

    /** @test */
    public function it_return_fillable_if_no_fillable_attributes_exists()
    {
        $this->lesson->fillable(['subject']);
        $this->assertEquals(['subject'], $this->lesson->getFillableAttributes());
        $this->assertEquals(['subject'], $this->lesson->getFillableAttributes('store'));
        $this->assertEquals(['subject'], $this->lesson->getFillableAttributes('update'));
    }

    /** @test */
    public function it_activate_all_lessons()
    {
        Factory::times(10)->create(Lesson::class);
        Lesson::activateAll();
        $this->assertDatabaseMissing(Lesson::TABLE, ['active' => '0']);
    }

    /** @test */
    public function it_deactivate_all_lessons()
    {
        Factory::times(10)->create(Lesson::class);
        Lesson::deactivateAll();
        $this->assertDatabaseMissing(Lesson::TABLE, ['active' => '1']);
    }
}
