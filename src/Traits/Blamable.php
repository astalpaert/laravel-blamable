<?php

namespace Astalpaert\LaravelBlamable\Traits;

use Illuminate\Database\Eloquent\SoftDeletes;

trait Blamable
{
    public static function bootBlamable()
    {
        // @TODO make $user->name configurable
        static::creating(function ($model) {
            $user = optional(auth()->user())->name ?? 'SYSTEM';
            $model->created_by = $user;
            $model->updated_by = $user;
        });

        static::updating(function ($model) {
            $user = optional(auth()->user())->name ?? 'SYSTEM';
            $model->updated_by = $user;
        });

        static::deleting(function ($model) {
            if ($model->usesSoftDeletes()) {
                $user = optional(auth()->user())->name ?? 'SYSTEM';
                $model->deleted_by = $user;
            }
        });
    }

    /**
     * @return bool
     */
    public function usesSoftDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses($this));
    }
}