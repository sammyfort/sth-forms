<?php

namespace App\Enums;

enum InstitutionInvestigator: string
{
    case STH = 'sth';
    case KBTH = 'kbth';
    case KATH = 'kath';
    case TTH = 'tth';
    case CCTH = 'ccth';
    case UENR = 'uenr';
    case KNUST = 'knust';
    case OTHER = 'other';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
