<?php

namespace App\Traits;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPromotion
{
    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'promotable');
    }

    public function latestPromotion(): ?Promotion
    {
        return $this->promotions()->latest()->first();
    }
}
