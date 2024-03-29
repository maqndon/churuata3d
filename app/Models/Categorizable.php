<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categorizable extends Model
{
    use HasFactory;

    public function categorizable(): MorphTo
    {
        return $this->morphTo();
    }
}
