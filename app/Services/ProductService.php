<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public static function getMostDownloaded($qty = 3)
    {
        return Product::with('images')
            ->where('status', 'published')
            ->orderBy('downloads', 'desc')
            ->take($qty)
            ->get();
    }
}