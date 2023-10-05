<?php

namespace App\Enums;

enum PostStatusEnum: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';
    case PENDING = 'pending';
}