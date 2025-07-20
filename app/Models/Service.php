<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
* @property string $title
* @property string $description
* @property string $first_mobile
* @property string $second_mobile
* @property string $email
* @property string $address
* @property string $region_id
* @property string $town
* @property Collection<ServiceCategory> $categories
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Media|null $featured
 */

class Service extends Model implements HasMedia, Viewable
{
    use BootModelTrait, HasFactory, InteractsWithMedia, InteractsWithViews;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('gallery');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'service_service_category',
            'service_id',
            'category_id'
        );
    }
}
