<?php

namespace Astalpaert\LaravelBlamable\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin Blueprint
 */
class AddBlamableFieldsMacro
{
    public function __invoke()
    {
        return function () {
            $tableName = $this->getTable();

            $addBlamableColumnIfExists = function (string $columnToAdd, string $columnAddedAfter) use ($tableName) {
                if (Schema::hasColumn($tableName, $columnToAdd)) {
                    return;
                }

                $column = $this->string($columnToAdd)->nullable();
                if (Schema::hasColumn($tableName, $columnAddedAfter)) {
                    $column->after($columnAddedAfter);
                }
            };

            $addBlamableColumnIfExists('created_by', 'created_at');
            $addBlamableColumnIfExists('updated_by', 'updated_at');
            $addBlamableColumnIfExists('deleted_by', 'deleted_at');
        };
    }
}
