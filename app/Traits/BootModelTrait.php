<?php

namespace App\Traits;



use Illuminate\Support\Str;

trait BootModelTrait
{
    public static function bootModels(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }
}
