<?php

namespace Astalpaert\LaravelBlamable\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait Blamable
{
    public static function bootBlamable()
    {
        $attributeName = config('astalpaert.blamable.user.attribute_name');
        $defaultUser = config('astalpaert.blamable.user.default');

        static::creating(static function ($model) use ($attributeName, $defaultUser): void {
            $user = optional(auth()->user())->$attributeName ?? $defaultUser;

            $model->created_by = $user;
            $model->updated_by = $user;
        });

        static::updating(function ($model) use ($attributeName, $defaultUser): void {
            $user = optional(auth()->user())->$attributeName ?? $defaultUser;

            $model->updated_by = $user;
        });

        static::deleting(static function ($model) use ($attributeName, $defaultUser): void {
            if ($model->usesSoftDeletes()) {
                $user = optional(auth()->user())->$attributeName ?? $defaultUser;

                $model->deleted_by = $user;
            }
        });
    }

    public function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses($this), true);
    }
}