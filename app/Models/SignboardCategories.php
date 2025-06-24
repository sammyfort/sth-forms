<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasSlug;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class SignboardCategories extends Model
{
    //
    use BootModelTrait, HasSlug;

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function signboards(): BelongsToMany
    {
        return $this->belongsToMany(
            SignboardCategories::class,
            'signboard_signboard_categories',
            'category_id',
            'signboard_id'
        );
    }
}
