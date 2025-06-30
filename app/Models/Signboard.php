<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Signboard extends Model
{
    use BootModelTrait, HasTags, HasFactory, ReviewRateable;

    protected $appends = [
        "total_average_rating",
        "reviews_count"
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            SignboardCategory::class,
            'signboard_signboard_categories',
            'signboard_id',
            'category_id'
        );
    }

    public function totalAverageRating(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->overallAverageRating()
        );
    }

    public function reviewsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->totalReviews() ?? 0
        );
    }
}
