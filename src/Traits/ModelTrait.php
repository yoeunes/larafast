<?php

namespace Yoeunes\Larafast\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

trait ModelTrait
{
    public $dataTableColumns = [];

    public $dataTableCustomConfiguration = [];

    public $dataTableEager = [];

    /**
     * @param int $perPage
     *
     * @return Paginator
     */
    public static function findAllPaginated(int $perPage = 20): Paginator
    {
        return static::latest()->paginate($perPage);
    }

    /**
     * @param self $model
     *
     * @return bool
     */
    public function matches(self $model): bool
    {
        return $this->id === $model->id;
    }

    /**
     * @return bool
     */
    public function activate(): bool
    {
        $this->active = true;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function deactivate(): bool
    {
        $this->active = false;

        return $this->save();
    }

    /**
     * @return $this
     */
    public function activateAndDeactivateOthers()
    {
        DB::transaction(function () {
            $this->deactivateAll();
            $this->update(['active' => true]);
        });

        return $this;
    }

    /**
     * @return mixed
     */
    public static function deactivateAll()
    {
        return DB::table(static::TABLE)->where('active', '=', 1)->update(['active' => 0]);
    }

    /**
     * @return mixed
     */
    public static function activateAll()
    {
        return DB::table(static::TABLE)->where('active', '=', 0)->update(['active' => 1]);
    }

    /**
     * @return mixed
     */
    public static function getActive()
    {
        return self::where('active', 1)->first();
    }

    /**
     * @return mixed
     */
    public static function getActives()
    {
        return self::where('active', 1)->get();
    }
}
