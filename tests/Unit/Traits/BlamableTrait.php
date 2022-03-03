<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit\Traits;

use Astalpaert\LaravelBlamable\Traits\Blamable;

trait BlamableTrait
{
    use Blamable;

    abstract protected function getModelClass(): string;

    protected function getModelInstance(): Blamable
    {
        $class = $this->getModelClass();

        return new $class();
    }

    /** @test */
    public function it_adds_created_by_when_creating(): void
    {
        $instance = $this->app[$this->getModelClass()]->create();

        $this->assertNotNull('created_at');
        $this->assertEquals('SYSTEM', $instance->created_by);
    }

    /** @test */
    public function it_adds_updated_by_when_updating(): void
    {
        $instance = $this->app[$this->getModelClass()]->create();
        $instance->touch();

        $this->assertNotNull('updated_at');
        $this->assertEquals('SYSTEM', $instance->updated_by);
    }

    /** @test */
    public function it_adds_deleted_by_when_deleting(): void
    {
        $instance = $this->app[$this->getModelClass()]->create();
        $instance->delete();

        if ($instance->usesSoftDeletes()) {
            $this->assertNotNull('deleted_at');
            $this->assertEquals('SYSTEM', $instance->deleted_by);
        }
    }
}