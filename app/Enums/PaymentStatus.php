<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case CANCELED = 'canceled';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
