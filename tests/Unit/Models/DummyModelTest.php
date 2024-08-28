<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit\Models;

use Astalpaert\LaravelBlamable\Tests\Support\Models\DummyModel;
use Astalpaert\LaravelBlamable\Tests\Support\Models\DummyUser;
use Astalpaert\LaravelBlamable\Tests\TestCase;
use Astalpaert\LaravelBlamable\Traits\Blamable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class DummyModelTest extends TestCase
{
    public const SOME_BLAMABLE_VALUE = 'Some blamable value';

    use Blamable;

    protected function getModelClass(): string
    {
        return DummyModel::class;
    }

    protected function getModelInstance(): Blamable
    {
        $class = $this->getModelClass();

        return new $class();
    }

    private function givenBlamable(null|DummyUser $value): string
    {
        if ($value instanceof DummyUser) {
            $this->actingAs($value);

            return $value->name;
        }
        if ($value === null) {
            return config('astalpaert.blamable.user.default');
        }
    }

    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_adds_created_by_and_updated_by_when_creating($value): void
    {
        $blamable = $this->givenBlamable($value);

        $instance = $this->app[$this->getModelClass()]->create();

        $this->assertEquals($blamable, $instance->created_by);
        $this->assertEquals($blamable, $instance->updated_by);
    }

    #[Test]
    public function it_keeps_created_by_when_creating_with_preset_value(): void
    {
        $blamable = $this->givenBlamable(null);
        $instance = $this->app[$this->getModelClass()]->create(
            [
                'created_by' => self::SOME_BLAMABLE_VALUE,
            ]
        );

        $this->assertEquals(self::SOME_BLAMABLE_VALUE, $instance->created_by);
        $this->assertEquals($blamable, $instance->updated_by);
    }

    #[Test]
    public function it_keeps_updated_by_when_creating_with_preset_value(): void
    {
        $blamable = $this->givenBlamable(new DummyUser('ARNO'));
        $instance = $this->app[$this->getModelClass()]->create(
            [
                'updated_by' => self::SOME_BLAMABLE_VALUE,
            ]
        );

        $this->assertEquals(self::SOME_BLAMABLE_VALUE, $instance->updated_by);
        $this->assertEquals($blamable, $instance->created_by);
    }

    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_updates_updated_by_when_updating($value): void
    {
        $blamable = $this->givenBlamable($value);

        $instance = $this->app[$this->getModelClass()]->create(
            [
                'updated_by' => self::SOME_BLAMABLE_VALUE,
            ]
        );

        $this->travel(1)->seconds();
        $instance->touch();

        $this->assertNotEquals(self::SOME_BLAMABLE_VALUE, $blamable);
        $this->assertEquals($blamable, $instance->updated_by);
    }

    #[Test]
    public function it_keeps_updated_by_when_updating_with_preset_value(): void
    {
        $blamable = $this->givenBlamable(null);

        $instance = $this->app[$this->getModelClass()]->create();
        $instance->update(
            [
                'updated_by' => self::SOME_BLAMABLE_VALUE,
            ]
        );


        $this->assertNotEquals(self::SOME_BLAMABLE_VALUE, $blamable);
        $this->assertEquals(self::SOME_BLAMABLE_VALUE, $instance->updated_by);
    }

    #[Test]
    #[DataProvider('configDataProvider')]
    public function it_adds_deleted_by_when_deleting($value): void
    {
        $blamable = $this->givenBlamable($value);

        $instance = $this->app[$this->getModelClass()]->create();
        $instance->delete();

        $this->assertTrue($instance->usesSoftDeletes());
        $this->assertEquals($blamable, $instance->deleted_by);
    }

    #[Test]
    public function it_keeps_deleted_by_when_soft_deleting_with_preset_value(): void
    {
        $blamable = $this->givenBlamable(null);

        $instance = $this->app[$this->getModelClass()]->create();
        $instance->deleted_by = self::SOME_BLAMABLE_VALUE;
        $instance->delete();

        $this->assertTrue($instance->usesSoftDeletes());
        $this->assertNotEquals(self::SOME_BLAMABLE_VALUE, $blamable);
        $this->assertEquals(self::SOME_BLAMABLE_VALUE, $instance->deleted_by);
    }


    public static function configDataProvider(): array
    {
        return [
            'default value' => [null],
            'user value' => [new DummyUser('ARNO')],
        ];
    }
}
