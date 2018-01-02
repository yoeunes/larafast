<?php

namespace Yoeunes\Larafast\Entities;

use Illuminate\Database\Eloquent\Model;
use Yoeunes\Larafast\Traits\BulkTrait;
use Yoeunes\Larafast\Traits\ExcelTrait;
use Yoeunes\Larafast\Traits\MediaTrait;
use Yoeunes\Larafast\Traits\ModelTrait;
use Yoeunes\Larafast\Traits\ValidationTrait;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @mixin \Illuminate\Database\Query\Builder
 */
class Entity extends Model
{
    use ModelTrait, MediaTrait, ValidationTrait, ExcelTrait, BulkTrait;

    const DEFAULT_IMAGE = 'https://placehold.it/100x100';
}
