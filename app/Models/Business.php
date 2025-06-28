<?php

namespace App\Models;

use App\Observers\BusinessObserver;
use App\Policies\BusinessPolicy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $uuid
 * @property string $slug
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

#[ObservedBy(BusinessObserver::class)]
#[UsePolicy(BusinessPolicy::class)]
class Business extends Model
{
    //
    use BootModelTrait, HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function signboards(): HasMany
    {
        return $this->hasMany(Signboard::class);
    }



}
