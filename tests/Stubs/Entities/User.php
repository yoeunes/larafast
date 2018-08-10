<?php

namespace Yoeunes\Larafast\Tests\Stubs\Entities;

use Illuminate\Notifications\Notifiable;
use Yoeunes\Larafast\Entities\User as BaseUser;

class User extends BaseUser
{
    use Notifiable;

    protected $connection = 'testbench';

    const TABLE = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $rules = [
        'name'     => 'required|string|max:255',
        'email'    => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
