<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bom extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'item',
    ];

    protected $casts = [
        'item' => 'array',
    ];

    public function bomable(): MorphTo
    {
        return $this->morphTo();
    }
}
