<?php

namespace App\Enums;

use App\Models\Service;
use App\Models\Signboard;
use http\Exception\InvalidArgumentException;

enum Promotable: string
{
    case SERVICE = 'service';
    case SIGNBOARD = 'signboard';
    case JOB = 'job';
    case SHOP = 'shop';

    public static function getModel(self $promotable): string
    {
        return match ($promotable){
            self::SERVICE => Service::class,
            self::SIGNBOARD => Signboard::class,
//            self::JOB => Service::class,
//            self::SERVICE => Service::class,
            default => throw new InvalidArgumentException()
        };
    }
}
