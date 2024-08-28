<?php

declare(strict_types=1);

namespace Astalpaert\LaravelBlamable\Tests\Support\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class DummyUser implements Authenticatable
{
    public function __construct(
        public readonly string $name
    ) {
    }

    public function getAuthIdentifierName() {}

    public function getAuthIdentifier() { }

    public function getAuthPasswordName() { }

    public function getAuthPassword() { }

    public function getRememberToken() { }

    public function setRememberToken($value) { }

    public function getRememberTokenName() { }

}
