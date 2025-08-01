<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PaymentPlatform: string implements HasLabel
{
    case HUBTEL = 'hubtel';
    case SYSTEM_POINTS = 'points';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
