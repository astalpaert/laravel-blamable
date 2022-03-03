<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit\Models;

use Astalpaert\LaravelBlamable\Tests\Support\Models\DummyModel;
use Astalpaert\LaravelBlamable\Tests\TestCase;
use Astalpaert\LaravelBlamable\Tests\Unit\Traits\BlamableTrait;

class DummyModelTest extends TestCase
{
    use BlamableTrait;

    protected function getModelClass(): string
    {
       return DummyModel::class;
    }
}