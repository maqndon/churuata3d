<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meta_description',
        'meta_keywords'
    ];

    protected $cast = [
        // 'meta_keywords' => 'array',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}