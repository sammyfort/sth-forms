<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class PromotionPlan extends Model
{
    //
    use BootModelTrait, HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'plan_id');
    }
}
