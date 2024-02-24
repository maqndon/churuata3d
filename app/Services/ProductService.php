<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public static function getParametric($slug)
    {

        $relatedParametric = Product::where('slug', $slug)->value('related_parametric');

        return $relatedParametric;
    }

    public static function getFileNames($slug): Array
    {
        // Find the product by Slug
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
        $relatedProducts = Product::with('images', 'categories')
            ->where('products.id', '<>', $product->id)
            ->whereHas('categories', function ($query) use ($product) {
                $query->whereIn('categories.id', $product->categories()->pluck('category_id'));
            })
            ->WhereHas('tags', function ($query) use ($product) {
                $query->whereIn('tags.id', $product->tags()->pluck('tag_id'));
            })
            ->take(4)
            ->get();

        return $relatedProducts;
    }

    public function setDownload($slug)
    {
        $downloads = self::getProduct($slug)->downloads;
        $downloads++;
        $downloads = self::getProduct($slug)->update(['downloads' => $downloads]);
        return $downloads;
    } 


}
