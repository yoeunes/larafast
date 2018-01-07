<?php

namespace Yoeunes\Larafast\Tests\Stubs\Policies;

use Yoeunes\Larafast\Policies\Policy;
use Yoeunes\Larafast\Tests\Stubs\Entities\Lesson;

class LessonPolicy extends Policy
{
    protected $entity = Lesson::class;
}
