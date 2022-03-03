<?php

namespace Astalpaert\LaravelBlamable\Tests;

use Astalpaert\LaravelBlamable\Providers\BlamableServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            BlamableServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setupDatabase()
    {
        include_once __DIR__.'/Support/Migrations/create_dummy_models_table.php.stub';

        (new \CreateDummyModelsTable())->up();
    }
}