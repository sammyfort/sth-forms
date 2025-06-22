<?php

namespace App\Traits;



use Illuminate\Support\Str;

trait BootModelTrait
{
    protected static function bootBootModelTrait(): void
    {
        static::creating(function (self $model){
            $model->created_by_id = auth()->check() ? auth()->id() : null;
            $model->uuid = Str::uuid()->toString();
        });
    }
}
