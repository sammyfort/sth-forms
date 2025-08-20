<?php

namespace App\Enums;

enum Title: string
{
    case MR = 'mr';
    case MRS = 'mrs';
    case MISS = 'miss';
    case DR = 'dr';
    case PROF = 'prof';
    case SIR = 'sir';
    case REV = 'rev';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
