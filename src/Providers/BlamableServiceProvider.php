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

            if (!Schema::hasColumn($tableName, 'created_by')) {
                $this->string('created_by')->after('created_at')->nullable();
            }

            if (!Schema::hasColumn($tableName, 'updated_by')) {
                $this->string('updated_by')->after('updated_at')->nullable();
            }

            if (Schema::hasColumn($tableName, 'deleted_at') && !Schema::hasColumn($tableName, 'deleted_by')) {
                $this->string('deleted_by')->after('deleted_at')->nullable();
            }
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