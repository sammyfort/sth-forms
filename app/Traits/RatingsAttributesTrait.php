<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait RatingsAttributesTrait
{
    public function totalAverageRating(): Attribute
    {
        return Attribute::make(
            get: fn() => ratingFormat($this->overallAverageRating())
        );
    }

    public function reviewsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->totalReviews() ?? 0
        );
    }
}
