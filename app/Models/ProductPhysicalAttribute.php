<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductPhysicalAttribute extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        Return $this->belongsTo(Product::class);
    }

}
