<?php

namespace App\Enums;

enum JobMode: string
{
    case ONSITE = 'onsite';

    case REMOTE = 'remote';

    case HYBRID = 'hybrid';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
