<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'item',
    ];

    // public function product()
    // {
    //     return $this->hasOne(Product::class);
    // }
}
