<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class bom extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
    ];

    // protected $casts = [
    //     'items' => 'array',
    // ];

    public function bomable(): MorphTo
    {
        return $this->morphTo();
    }
}
