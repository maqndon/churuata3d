<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class CategoryService
{
    public function storeCategory(array $data)
    {
        $category = new Category();
        $category->name = $data['name'];
        $category->slug = $data['slug'];
        $category->save();

        return $category;
    }

    public function updateCategory(Category $category, array $data)
    {
        // Filter the data that has changed
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        // Update the category with the filtered data
        $category->update($data);

        return $category;
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return true;
    }

    public function attachCategory(Product $product, Category $category)
    {
        $product->categories()->attach($category);

        return true;
    }

    public function detachCategory(Product $product, Category $category)
    {
        $product->categories()->detach($category);

        return true;
    }
}
