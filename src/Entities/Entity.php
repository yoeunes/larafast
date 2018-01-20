<?php

namespace Yoeunes\Larafast\Entities;

use Yoeunes\Larafast\Traits\BulkTrait;
use Illuminate\Database\Eloquent\Model;
use Yoeunes\Larafast\Traits\ExcelTrait;
use Yoeunes\Larafast\Traits\FilesTrait;
use Yoeunes\Larafast\Traits\ModelTrait;
use Yoeunes\Larafast\Traits\ValidationTrait;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @mixin \Illuminate\Database\Query\Builder
 */
class Entity extends Model
{
    use ModelTrait, ValidationTrait, FilesTrait, ExcelTrait, BulkTrait;
}
