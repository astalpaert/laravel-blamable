<?php

namespace Astalpaert\LaravelBlamable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

trait Blamable
{
    public static function bootBlamable(): void
    {
        $attributeName = config('astalpaert.blamable.user.attribute_name');
        $defaultUser = config('astalpaert.blamable.user.default');

        static::creating(static function (Model $model) use ($attributeName, $defaultUser): void {
            $user = optional(auth()->user())->$attributeName ?? $defaultUser;

            if (!$model->isDirty('created_by')) {
                $model->created_by = $user;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = $user;
            }
        });

        static::updating(function ($model) use ($attributeName, $defaultUser): void {
            if ($model->isDirty('updated_by')) {
                return;
            }
            $user = optional(auth()->user())->$attributeName ?? $defaultUser;

            $model->updated_by = $user;
        });

        static::deleting(static function ($model) use ($attributeName, $defaultUser): void {
            if ($model->usesSoftDeletes()) {
                if ($model->isDirty('deleted_by')) {
                    return;
                }
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