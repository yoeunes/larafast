<?php

namespace Yoeunes\Larafast\Tests\Stubs\Entities;

use Yoeunes\Larafast\Entities\Entity;

class Lesson extends Entity
{
    protected $connection = 'testbench';

    const TABLE = 'lessons';

    protected $fillable = [
        'title',
        'subject',
    ];

    protected $rules = [
        'title'   => 'required|max:255',
        'subject' => 'required|max:255',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
