<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
* @property string $created_at
* @property string $updated_at
*/

class SignboardSubscription extends Model
{
    //
    use BootModelTrait, HasFactory;

    public function signboard(): BelongsTo
    {
        return $this->belongsTo(Signboard::class, 'signboard_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SignboardSubscriptionPlan::class, 'plan_id');
    }
}
