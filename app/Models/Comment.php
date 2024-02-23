<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment'
    ];

    protected $cast = [
        'comment' => 'array'
    ];

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }
}
