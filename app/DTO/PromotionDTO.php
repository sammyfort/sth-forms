<?php

namespace App\DTO;

use App\Enums\PaymentStatus;
use Illuminate\Support\Carbon;

class PromotionDTO
{
    public function __construct(
        public string $payment_reference,
        public PaymentStatus $payment_status,
        public ?int $plan_id,
        public ?float $amount,
        public ?string $checkout_id,
        public ?string $payment_platform,
        public ?string $payment_channel,
        public ?Carbon $starts_at,
        public ?Carbon $ends_at,
        public ?string $receipt_number,
        public ?int $created_by_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            payment_reference: $data['payment_reference'],
            payment_status: $data['payment_status'] ?? PaymentStatus::PENDING,
            plan_id: $data['plan_id'] ?? null,
            amount: $data['amount'] ?? null,
            checkout_id: $data['checkout_id'] ?? null,
            payment_platform: $data['payment_platform'] ?? null,
            payment_channel: $data['payment_channel'] ?? null,
            starts_at: isset($data['starts_at']) ? Carbon::parse($data['starts_at']) : null,
            ends_at: isset($data['ends_at']) ? Carbon::parse($data['ends_at']) : null,
            receipt_number: $data['receipt_number'] ?? null,
            created_by_id: $data['created_by_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'plan_id' => $this->plan_id,
            'amount' => $this->amount,
            'payment_reference' => $this->payment_reference,
            'checkout_id' => $this->checkout_id,
            'payment_platform' => $this->payment_platform,
            'payment_status' => $this->payment_status->value,
            'payment_channel' => $this->payment_channel,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'receipt_number' => $this->receipt_number,
            'created_by_id' => $this->created_by_id,
        ];
    }
}
