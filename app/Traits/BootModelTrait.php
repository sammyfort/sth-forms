<?php

namespace App\Traits;



use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
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

    public function createdAtStr(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->format('D, d M Y H:i'),
        );
    }
}
