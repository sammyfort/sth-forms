<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class Business extends Model
{
    //
    use BootModelTrait;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signboards(): HasMany
    {
        return $this->hasMany(Signboard::class);
    }

}
