<?php

namespace Yoeunes\Larafast\Policies;

use Yoeunes\Larafast\Entities\User;
use Yoeunes\Larafast\Traits\EntityTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use EntityTrait, HandlesAuthorization;

    protected $allowed = [];

    /**
     * @param $name
     * @param $arguments
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    public function __call($name, $arguments)
    {
        $user = $arguments[0] instanceof User ? $arguments[0] : null;

        return $this->authorize($name, $user);
    }

    /**
     * @param string $function
     * @param User   $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return bool
     */
    private function authorize(string $function, User $user): bool
    {
        if (! in_array($function, $this->allowed) && false === $user->can(str_plural(strtolower($this->entityBaseName())).' '.$function)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('This action is unauthorized.', 401);
        }

        return true;
    }
}
