<?php

namespace App\Traits;



use Illuminate\Support\Str;

trait BootModelTrait
{
    public static function bootModels(): void
    {
        parent::boot();
        static::created(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
