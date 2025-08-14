<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 */
class Country extends Model
{
    //
    use BootModelTrait;

    public function regions(): HasMany{
        return $this->hasMany(Region::class);
    }
}
