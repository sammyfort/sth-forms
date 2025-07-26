<?php

namespace App\Enums;

enum JobStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    case PAUSED = 'paused';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
