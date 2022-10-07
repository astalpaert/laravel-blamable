<?php

namespace Astalpaert\LaravelBlamable\Providers;

use Astalpaert\LaravelBlamable\Database\AddBlamableFieldsMacro;
use Astalpaert\LaravelBlamable\Database\RemoveBlamableFieldsMacro;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BlamableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__, 2) . '/config/astalpaert-blamable.php' => config_path('astalpaert-blamable.php'),
        ]);

        if ($this->app->runningInConsole()) {
            Blueprint::macro('addBlamableFields', $this->app->call(AddBlamableFieldsMacro::class));
            Blueprint::macro('removeBlamableFields', $this->app->call(RemoveBlamableFieldsMacro::class));
        }
    }

    public function register()
    {
        parent::register();

        $this->mergeConfigFrom(
            dirname(__DIR__, 2) . '/config/astalpaert-blamable.php',
            'astalpaert.blamable'
        );
    }
}
