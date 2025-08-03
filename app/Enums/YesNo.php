<?php

namespace App\Enums;

enum YesNo: string
{
    case YES = 'yes';
    case NO = 'no';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function toBool(): bool
    {
        return $this === self::YES;
    }
}
