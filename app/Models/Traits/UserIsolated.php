<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait UserIsolated
{
    public static function bootUserIsolated(): void
    {
        static::addGlobalScope('user_id', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && ! $model->user_id) {
                $model->user_id = Auth::id();
            }
        });
    }
}
