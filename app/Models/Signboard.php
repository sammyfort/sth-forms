<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Observers\SignboardObserver;
use App\Traits\BootModelTrait;
use Codebyray\ReviewRateable\Models\Review;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Translatable\HasTranslations;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property Collection<Review> $reviews
 * @property int|null $views_count
 * @property string|null $featured_url
 * @property string $gps
 * @property string $gps_lat
 * @property string $gps_lon
 */
#[ObservedBy(SignboardObserver::class)]
class Signboard extends Model implements HasMedia, Viewable
{
    use BootModelTrait, HasTags, HasFactory, ReviewRateable, InteractsWithMedia, InteractsWithViews, HasSlug;


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile();
        $this->addMediaCollection('gallery');
    }

    protected $appends = [
        "total_average_rating",
        "reviews_count",
        "created_at_str",
        "active_subscription"
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SignboardSubscription::class, 'signboard_id');
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
            get: fn() => ratingFormat($this->overallAverageRating())
        );
    }

    public function reviewsCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->totalReviews() ?? 0
        );
    }

    public function activeSubscription(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->subscriptions()
                ->running()
                ->first()
        );
    }

    public function toArrayWithMedia(): array
    {
        $data = $this->toArray();
        $featuredMedia = $this->getFirstMedia('featured');
        $data['featured_url'] = $featuredMedia ? $featuredMedia->getUrl() : null;

        $galleryMedia = $this->getMedia('gallery');
        $data['gallery_urls'] = $galleryMedia->map(fn($media) => $media->getUrl())->toArray();

        return $data;
    }
}
