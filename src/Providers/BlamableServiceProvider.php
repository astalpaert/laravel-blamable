<?php

namespace Astalpaert\LaravelBlamable\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BlamableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__, 2).'/config/astalpaert-blamable.php' => config_path('astalpaert-blamable.php'),
            ], 'config');
        }

        $this->registerBlamableFieldMacros();
    }

    private function registerBlamableFieldMacros()
    {
        // up
        Blueprint::macro('addBlamableFields', function () {
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
        });

        // down
        Blueprint::macro('removeBlamableFields', function () {
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
        });
    }

    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/astalpaert-blamable.php',
            'astalpaert.blamable.configuration'
        );
    }
}