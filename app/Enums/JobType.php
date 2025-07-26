<?php

namespace App\Enums;

enum JobType: string
{

    case PARTTIME = 'part-time';
    case FULLTIME = 'full-time';
    case CONTRACT = 'contract';


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
