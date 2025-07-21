<?php

namespace App\Traits;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPromotion
{
    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'promotable');
    }

    public function activePromotion(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->promotions()
                ->running()
                ->first()
        );
    }
}
