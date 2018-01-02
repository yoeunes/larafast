<?php

namespace Yoeunes\Larafast\Tests\Stubs\Controllers\Web;

use Yoeunes\Larafast\Controllers\WebController;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

class LessonController extends WebController
{
    protected $entity = Lesson::class;
}
