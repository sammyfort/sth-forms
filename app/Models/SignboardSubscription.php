<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Traits\BootModelTrait;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
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
 * @property string $ends_at
 * @property string $starts_at
 * @property bool $payment_status
 */

class SignboardSubscription extends Model
{
    //
    use BootModelTrait, HasFactory;

    protected $appends = [
        'is_active',
        'days_left',
        'total_days',
    ];

    public $casts = [
        'payment_status' => PaymentStatus::class,
    ];

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
            get: fn () =>
                $this->payment_status == PaymentStatus::PAID &&
                !is_null($this->ends_at) &&
                Carbon::parse($this->ends_at)->isFuture()
        );
    }


    public function scopeRunning(Builder $query)
    {
        $now = Carbon::now();

        $query
            ->where('payment_status', PaymentStatus::PAID)
            ->whereNotNull('ends_at')
            ->whereDate('ends_at', '>', now())
            ->latest();
    }

    public function totalDays(): Attribute
    {
        return Attribute::make(
            get: function (){
                if ($this->starts_at && $this->ends_at){
                    return (int) Carbon::parse($this->starts_at)->diffInDays(Carbon::parse($this->ends_at));
                }
                return 0;
            }
        );
    }

    public function daysLeft(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ends_at ? (int) now()->diffInDays($this->ends_at) : 0
        );
    }
}
