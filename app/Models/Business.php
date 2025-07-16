<?php

namespace App\Models;

use App\Policies\BusinessPolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $id
 * @property string $uuid
 * @property string $slug
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
 * @property string $name
 */

#[UsePolicy(BusinessPolicy::class)]
class Business extends Model
{
    //
    use BootModelTrait, HasFactory, HasSlug;

    protected $appends = [
        "created_at_str",
        "initials"
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signboards(): HasMany
    {
        return $this->hasMany(Signboard::class);
    }

    public function initials(): Attribute
    {
        return Attribute::make(
            get: fn () => strtoupper($this->name[0]). strtoupper($this->name[1])
        );
    }

    public function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => ratingFormat(
                $this->signboards()
                    ->with('ratings')
                    ->get()
                    ->pluck('overallAverageRating')
                    ->filter()
                    ->avg()
            )
        );
    }

}
