<?php

namespace App\Enums;

enum JobModeOfApply: string
{
    case EXTERNAL = 'external_link';

    case INSTRUCTION = 'instruction';

    case BOTH = 'both';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
