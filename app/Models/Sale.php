<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class Sale extends Model
{
    use HasFactory;

    public function product(): BelongsTo
    {
        Return $this->belongsTo(Product::class);
    }
}
