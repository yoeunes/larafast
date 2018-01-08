<?php

namespace Yoeunes\Larafast\Tests\Stubs\Controllers\Api;

use Yoeunes\Larafast\Controllers\ApiController;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

class LessonController extends ApiController
{
    protected $entity = Lesson::class;
}
