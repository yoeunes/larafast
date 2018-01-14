<?php

namespace Yoeunes\Larafast\Entities;

use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Illuminate\Notifications\Notifiable;

class User extends Entity implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    HasMedia
{
    use Notifiable, Authenticatable, Authorizable, CanResetPassword, HasApiTokens, HasRoles;

    /**
     * @param string $value
     */
    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
