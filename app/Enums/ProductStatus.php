<?php

namespace App\Enums;

enum ProductStatus: string
{
    case PUBLISHED = 'Published';
    case DRAFT = 'Draft';
}