<?php

namespace App\Traits;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use \Illuminate\Database\Eloquent\Builder;

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

    public static function getAllPromotions(): Collection
    {
        return self::getAllPromotionsQuery()->get();
    }

    public static function getAllPromotionsQuery(): Builder
    {
        return Promotion::query()
            ->whereHasMorph('promotable', [static::class]);
    }

    public static function getRandomPromotedQuery(int $take = 10): Builder
    {
        return self::query()
            ->whereHas('promotions', function (Builder $promotionQuery) {
                $promotionQuery->running();
            })
            ->where('created_by_id', '!=', auth()->id())
            ->inRandomOrder()
            ->take($take);
    }
}
