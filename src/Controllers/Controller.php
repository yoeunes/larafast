<?php

namespace Yoeunes\Larafast\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Yoeunes\Larafast\Middlewares\BlacklistRoutes;
use Yoeunes\Larafast\Traits\BlacklistTrait;
use Yoeunes\Larafast\Traits\EntityTrait;
use Yoeunes\Larafast\Traits\GateTrait;
use Yoeunes\Larafast\Traits\JobTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, EntityTrait, GateTrait, JobTrait, BlacklistTrait;

    public function __construct()
    {
        $this->middleware(BlacklistRoutes::class);
    }
}
