<?php

namespace App\Enums;

enum ProductStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

}
