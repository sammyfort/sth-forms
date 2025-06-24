<?php

namespace App\Models;

use App\Traits\BootModelTrait;
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
    //
    use BootModelTrait,HasTags;

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            SignboardCategories::class,
            'signboard_signboard_categories',
            'signboard_id',
            'category_id'
        );
    }
}
