<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    protected $appends = ['is_active'];
    public function signboard(): BelongsTo
    {
        return $this->belongsTo(Signboard::class, 'signboard_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SignboardSubscriptionPlan::class, 'plan_id');
    }

    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starts_at <= now() &&
                (is_null($this->ends_at) || $this->ends_at >= now())
        );
    }


    public function scopeRunning($query)
    {
        $now = Carbon::now();

        return $query->where('starts_at', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }
}
