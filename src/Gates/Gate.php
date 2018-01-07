<?php

namespace Yoeunes\Larafast\Gates;

use Illuminate\Auth\Access\HandlesAuthorization;
use Yoeunes\Larafast\Traits\EntityTrait;

class Gate
{
    use EntityTrait, HandlesAuthorization;
}
