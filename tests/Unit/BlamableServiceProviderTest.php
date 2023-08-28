<?php

namespace Astalpaert\LaravelBlamable\Tests\Unit;

use Astalpaert\LaravelBlamable\Tests\TestCase;
use Illuminate\Database\Schema\Blueprint;

class BlamableServiceProviderTest extends TestCase
{
    /** @test **/
    public function it_registers_macros()
    {
        $this->assertTrue(Blueprint::hasMacro('addBlamableFields'));
        $this->assertTrue(Blueprint::hasMacro('addBlamableFields'));
    }
}
