<?php

namespace Yoeunes\Larafast\Controllers;

use Illuminate\Support\Facades\Gate;
use Yoeunes\Larafast\Traits\JobTrait;
use Yoeunes\Larafast\Traits\EntityTrait;
use Yoeunes\Larafast\Traits\PolicyTrait;
use Yoeunes\Larafast\Traits\AbilityTrait;
use Yoeunes\Larafast\Traits\ServiceTrait;
use Yoeunes\Larafast\Traits\ResponseTrait;
use Yoeunes\Larafast\Traits\BlacklistTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Yoeunes\Larafast\Traits\TransformerTrait;
use Yoeunes\Larafast\Middlewares\BlacklistRoutes;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, TransformerTrait, ResponseTrait, ValidatesRequests, EntityTrait, ServiceTrait, PolicyTrait, JobTrait, BlacklistTrait, AbilityTrait;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->middleware(BlacklistRoutes::class);

        Gate::policy($this->entityName(), $this->getPolicy());
    }
}
