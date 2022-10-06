<?php

namespace Astalpaert\LaravelBlamable\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin Blueprint
 */
class RemoveBlamableFieldsMacro
{
    public function __invoke()
    {
        return function () {
            $tableName = $this->getTable();

            if (Schema::hasColumn($tableName, 'created_by')) {
                $this->dropColumn('created_by');
            }

            if (Schema::hasColumn($tableName, 'updated_by')) {
                $this->dropColumn('updated_by');
            }

            if (Schema::hasColumn($tableName, 'deleted_by')) {
                $this->dropColumn('deleted_by');
            }
        };
    }
}
