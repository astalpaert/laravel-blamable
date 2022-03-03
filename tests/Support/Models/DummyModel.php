<?php

namespace Astalpaert\LaravelBlamable\Tests\Support\Models;

use Astalpaert\LaravelBlamable\Traits\Blamable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DummyModel extends Model
{
    use Blamable;
    use SoftDeletes;

    protected $guarded = [];
}