<?php

namespace App\Enums;

enum PostStatus: string
{
    case PUBLISHED = 'Published';
    case DRAFT = 'Draft';
    case PENDING = 'Pending';
}