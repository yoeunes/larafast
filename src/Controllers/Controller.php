<?php

namespace Yoeunes\Larafast\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Gate;
use Yoeunes\Larafast\Middlewares\BlacklistRoutes;
use Yoeunes\Larafast\Traits\AbilityTrait;
use Yoeunes\Larafast\Traits\BlacklistTrait;
use Yoeunes\Larafast\Traits\EntityTrait;
use Yoeunes\Larafast\Traits\JobTrait;
use Yoeunes\Larafast\Traits\PolicyTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, EntityTrait, PolicyTrait, JobTrait, BlacklistTrait, AbilityTrait;

    public function __construct()
    {
        $this->middleware(BlacklistRoutes::class);

        Gate::policy($this->entityName(), $this->getPolicy());
    }
}
