<?php

namespace App\Enums;

enum PrintSettings: string
{
    case HOLLOW = 'Hollow';
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
    case SOLID = 'Solid';
}