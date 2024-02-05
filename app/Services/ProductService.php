<?php

namespace App\Services;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use PhpParser\Node\Expr\Cast\Array_;

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

    public static function getParametric($slug)
    {

        $relatedParametric = Product::where('slug', $slug)->value('related_parametric');

        return $relatedParametric;
    }

    public static function getFileNames($slug): Array
    {
        // Find the product by ID
        $product = self::getProduct($slug);

        // Get the files associated with the product
        $fileNames = $product->files->files_names;

        return $fileNames;
    }

    public static function getProduct($slug): Product
    {
        // Find the product by Slug
        $product = Product::where('slug', $slug)->first();

        return $product;
    }

    public function getRelatedProducts(Product $product)
    {
        $relatedProducts = Product::with('images')
            ->where('id', '<>', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('id', $product->categories()->pluck('id'));
            })
            ->WhereHas('tags', function ($query) use ($product) {
                $query->whereIn('id', $product->tags()->pluck('id'));
            })
            ->take(4)
            ->get();

        return $relatedProducts;
    }

}
