<?php

namespace App\Enums;

enum StaffCategory: string
{
    case NURSES = 'nurses';
    case DOCTORS = 'doctors';
    case PHARMACISTS = 'pharmacists';

    case LABORATORY_SCIENTISTS = 'laboratory-scientists';
    case NON_APPLICABLE = 'non-applicable';

    case OTHER = 'other';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }


}
