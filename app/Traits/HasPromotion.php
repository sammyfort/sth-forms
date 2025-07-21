<?php

namespace App\Traits;

use App\DTO\PromotionDTO;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPromotion
{
    public function promotions(): MorphMany
    {
        return $this->morphMany(Promotion::class, 'promotable');
    }

    public function addPromotion(PromotionDTO $data): Promotion
    {
        return $this->promotions()->create($data->toArray());
    }

    public function latestPromotion(): ?Promotion
    {
        return $this->promotions()->latest()->first();
    }
}
