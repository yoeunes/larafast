<?php

namespace Yoeunes\Larafast\Controllers;

use Yoeunes\Larafast\Policies\Policy;
use Yoeunes\Larafast\Traits\DataTableScopeTrait;
use Yoeunes\Larafast\Traits\DataTableTrait;
use Yoeunes\Larafast\Traits\ServiceTrait;
use Yoeunes\Larafast\Traits\ViewTrait;

class WebController extends Controller
{
    use ViewTrait, DataTableTrait, DataTableScopeTrait, ServiceTrait;

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        if (is_a($this->policy, Policy::class, true)) {
            $this->authorize(__FUNCTION__, $this->entityName());
        }

        return view($this->getView(__FUNCTION__));
    }
}
