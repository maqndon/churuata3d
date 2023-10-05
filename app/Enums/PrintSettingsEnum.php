<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case HOLLOW = 'hollow';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case SOLID = 'solid';
}