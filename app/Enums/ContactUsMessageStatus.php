<?php

namespace App\Enums;

enum ContactUsMessageStatus: string
{
    case NEW = 'new';
    case REVIEWING = 'reviewing';
    case REVIEWED = 'reviewed';
}
