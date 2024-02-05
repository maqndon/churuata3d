<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCommonContent extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'content'];
    
}
