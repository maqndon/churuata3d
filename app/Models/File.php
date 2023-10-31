<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'files_names',
        'metadata'
    ];

    protected $casts = [
        'files_names' => 'array',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
