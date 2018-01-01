<?php

namespace Yoeunes\Larafast\Traits;

use Illuminate\Pagination\Paginator;

trait ModelTrait
{
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
    public function active(): bool
    {
        return (bool) $this->active;
    }

    /**
     * @return bool
     */
    public function notActive(): bool
    {
        return !$this->active();
    }

    /**
     * @return bool
     */
    public function activate(): bool
    {
        return $this->update(['active' => true]);
    }

    /**
     * @return bool
     */
    public function deactivate(): bool
    {
        return $this->update(['active' => false]);
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
    public function deactivateAll()
    {
        return DB::table(self::TABLE)->where('active', '=', 1)->update(['active' => 0]);
    }

    /**
     * @return mixed
     */
    public function activateAll()
    {
        return DB::table(self::TABLE)->where('active', '=', 0)->update(['active' => 1]);
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