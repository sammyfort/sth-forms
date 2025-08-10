<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Region extends Model
{
    //
    use BootModelTrait, HasFactory, HasSlug;

    protected $appends = [
        "created_at_str"
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function signboards(): HasMany
    {
        return $this->hasMany(Signboard::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
