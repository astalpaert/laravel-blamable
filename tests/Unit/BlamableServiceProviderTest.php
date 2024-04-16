<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit;

use Astalpaert\LaravelBlamable\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\Attributes\Test;

class BlamableServiceProviderTest extends TestCase
{
    #[Test]
    public function it_has_registers_macros()
    {
        $this->assertTrue(Blueprint::hasMacro('addBlamableFields'));
        $this->assertTrue(Blueprint::hasMacro('addBlamableFields'));
    }
}
