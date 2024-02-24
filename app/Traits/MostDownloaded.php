<?php

namespace App\Traits;

use App\Models\Product;

trait MostDownloaded
{

public static function mostDownloaded($qty = 3)
    {
        return Product::with('images', 'categories')
            ->where('status', 'published')
            ->orderBy('downloads', 'desc')
            ->take($qty)
            ->get();
    }
}