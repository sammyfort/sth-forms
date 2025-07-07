<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContactUsMessageStatus: string implements HasLabel
{
    case NEW = 'new';
    case REVIEWING = 'reviewing';
    case REVIEWED = 'reviewed';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
