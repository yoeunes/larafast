<?php

namespace Yoeunes\Larafast\Traits;

trait BulkTrait
{
    /**
     * Mass (bulk) insert or update on duplicate for Laravel 4/5
     *
     * insertOrUpdate([
     *   ['id'=>1,'value'=>10],
     *   ['id'=>2,'value'=>60]
     * ]);
     *
     *
     * @param array $rows
     *
     * @return bool
     */
    public function insertOrUpdate(array $rows): bool
    {
        $table = \DB::getTablePrefix().with(new static)->getTable();

        $first = reset($rows);

        $columns = implode(',', array_map(function ($value) { return "$value"; }, array_keys($first)));

        $values = implode(',', array_map(function ($row) {return '('.implode(',', array_map(function ($value) { return '"'.str_replace('"', '""', $value).'"'; }, $row)).')';}, $rows));

        $updates = implode(',', array_map(function ($value) { return "$value = VALUES($value)"; }, array_keys($first)));

        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return \DB::statement($sql);
    }
}
