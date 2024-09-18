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

    public static function getFileNames($slug): array
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

    public function storeProduct(array $data)
    {
        $product = new Product();
        $product->created_by = $data['created_by'];
        $product->licence_id = $data['licence_id'];
        $product->title = $data['title'];
        $product->slug = $data['slug'];
        $product->sku = $data['sku'];
        $product->excerpt = $data['excerpt'];
        $product->body = $data['body'];
        $product->stock = $data['stock'];
        $product->price = $data['price'];
        $product->sale_price = $data['sale_price'];
        $product->status = $data['status'];
        $product->is_featured = $data['is_featured'];
        $product->is_downloadable = $data['is_downloadable'];
        $product->is_free = $data['is_free'];
        $product->is_printable = $data['is_printable'];
        $product->is_parametric = $data['is_parametric'];
        $product->related_parametric = $data['related_parametric'];
        $product->downloads = $data['downloads'];
        $product->save();

        return $product;
    }


    public function updateProduct(Product $product, array $data)
    {
        // Filter the data that has changed
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        // Update the product with the filtered data
        $product->update($data);

        return $product;
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return true;
    }
}
