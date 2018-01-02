<?php

namespace Yoeunes\Larafast\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Yoeunes\Larafast\Traits\EntityTrait;

class Policy
{
    use EntityTrait, HandlesAuthorization;
}
