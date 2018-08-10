<?php

namespace Yoeunes\Larafast\Tests\Stubs\DataTables;

use Yoeunes\Larafast\DataTables\DataTable;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

class LessonDataTable extends DataTable
{
    protected $entity = Lesson::class;
}
