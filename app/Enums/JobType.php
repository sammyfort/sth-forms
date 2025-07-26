<?php

namespace App\Enums;

enum JobType: string
{

    case PARTTIME = 'Part time';
    case FULLTIME = 'Full time';
    case CONTRACT = 'Contract';
    case INTERNSHIP = 'Internship';


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
