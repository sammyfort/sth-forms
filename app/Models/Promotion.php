<?php

namespace App\Models;

use App\Enums\PaymentPlatform;
use App\Enums\PaymentStatus;
use App\Traits\BootModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property PaymentStatus $payment_status
 * @property mixed $ends_at
 * @property mixed $starts_at
 */
class Promotion extends Model
{
    //
    use BootModelTrait;

    protected $appends = [
        'is_active',
        'days_left',
        'total_days',
    ];

    public $casts = [
        'payment_status' => PaymentStatus::class,
        'payment_platform' => PaymentPlatform::class,
    ];

    public function promotable(): MorphTo
    {
        return $this->morphTo();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PromotionPlan::class);
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

    public function scopeRunning(Builder $query): void
    {
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

    public static function routeCallback()
    {
        $paymentStatus = request()->get('payment_status');
        $paymentReference = request()->get('reference');
        if ($paymentStatus && $paymentReference) {
            $promotion = Promotion::query()
                ->where('payment_reference', $paymentReference)
                ->where('payment_status', PaymentStatus::PENDING)
                ->orWhere(function (Builder $builder){
                    $builder->where('payment_status', PaymentStatus::PAID)
                        ->where('payment_platform', PaymentPlatform::SYSTEM_POINTS);
                })
                ->first();
            if ($promotion) {
                if ($paymentStatus == 'cancelled') {
                    $promotion->payment_status = PaymentStatus::CANCELED;
                }
                $promotion->save();
            }
            else {
                $paymentStatus = null;
            }
        }
        return $paymentStatus;
    }
}
