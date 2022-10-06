<?php

namespace Astalpaert\LaravelBlamable\Database;

use Illuminate\Database\Schema\Blueprint;

/**
 * @mixin Blueprint
 */
class RemoveBlamableFieldsMacro
{
    public function __invoke()
    {
        return function () {
            $this->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        };
    }
}
