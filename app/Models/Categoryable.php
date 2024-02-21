<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoryable extends Model
{
    use HasFactory;

    public function categoryable(): MorphTo
    {
        return $this->morphTo();
    }
}
