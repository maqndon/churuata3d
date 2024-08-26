<?php

namespace App\Traits;

use App\Models\Product;

trait MostDownloaded
{

    public static function mostDownloaded($qty = 3)
    {
        return Product::published()
            ->withDefaultRelationships()
            ->mostDownloaded($qty)
            ->get();
        // return Product::with('images', 'categories')
        //     ->where('status', 'published')
        //     ->orderBy('downloads', 'desc')
        //     ->take($qty)
        //     ->get();
    }
}
