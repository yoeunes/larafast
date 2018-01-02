<?php

namespace Yoeunes\Larafast\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Yoeunes\Larafast\Traits\BlacklistTrait;
use Yoeunes\Larafast\Traits\EntityTrait;
use Yoeunes\Larafast\Traits\JobTrait;
use Yoeunes\Larafast\Traits\PolicyTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, EntityTrait, PolicyTrait, JobTrait, BlacklistTrait;
}
