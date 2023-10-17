<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintSupportRaft extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'has_supports',
        'has_raft',
    ];

}
