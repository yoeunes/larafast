<?php

namespace Yoeunes\Larafast\Controllers;

use Barryvdh\Cors\HandleCors;
use Laravel\Passport\Http\Middleware\CreateFreshApiToken;

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware(['api', CreateFreshApiToken::class, HandleCors::class]);
    }
}
