<?php

namespace App\Enums;

enum PrintSettings: string
{
    case HOLLOW = 'hollow';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
    case SOLID = 'solid';
}