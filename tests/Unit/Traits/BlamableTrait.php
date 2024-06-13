<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit\Traits;

use Astalpaert\LaravelBlamable\Traits\Blamable;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

trait BlamableTrait
{
    use Blamable;

    abstract protected function getModelClass(): string;

    protected function getModelInstance(): Blamable
    {
        $class = $this->getModelClass();

        return new $class();
    }

    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_adds_created_by_when_creating($description, $defaultValue): void
    {
        Config::set('astalpaert.blamable.user.default', $defaultValue);

        $instance = $this->app[$this->getModelClass()]->create();

        $this->assertNotNull('created_at');
        $this->assertEquals($defaultValue, config('astalpaert.blamable.user.default'));
        $this->assertEquals($defaultValue, $instance->created_by);
    }

    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_adds_updated_by_when_updating($description, $defaultValue): void
    {
        Config::set('astalpaert.blamable.user.default', $defaultValue);

        $instance = $this->app[$this->getModelClass()]->create();
        $instance->touch();

        $this->assertNotNull('updated_at');
        $this->assertEquals($defaultValue, config('astalpaert.blamable.user.default'));
        $this->assertEquals($defaultValue, $instance->updated_by);
    }


    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_adds_deleted_by_when_deleting($description, $defaultValue): void
    {
        Config::set('astalpaert.blamable.user.default', $defaultValue);

        $instance = $this->app[$this->getModelClass()]->create();
        $instance->delete();

        if ($instance->usesSoftDeletes()) {
            $this->assertNotNull('deleted_at');
            $this->assertEquals($defaultValue, config('astalpaert.blamable.user.default'));
            $this->assertEquals($defaultValue, $instance->deleted_by);
        }
    }

    #[Test]
    public function it_uses_given_name_instead_of_fetching_it_from_user(): void
    {
        $instance = $this->app[$this->getModelClass()]->create([
            'created_by' => 'Ignace',
            'updated_by' => 'Jean-Paul',
            'deleted_by' => 'Arno',
        ]);

        $this->assertEquals('Ignace', $instance->created_by);
        $this->assertEquals('Jean-Paul', $instance->updated_by);
        $this->assertEquals('Arno', $instance->deleted_by);
    }

    public static function configDataProvider(): array
    {
        return [
            ['default value', 'SYSTEM'],
            ['customized value', 'ARNO'],
        ];
    }
}