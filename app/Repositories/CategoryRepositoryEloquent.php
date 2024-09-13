<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryRepositoryEloquent implements CategoryRepositoryInterface
{
    public function attachCategory(Request $request, Product $product, Category $category)
    { 
        $product->categories()->syncWithoutDetaching([$category->id]);
    }

    public function detachCategory(Request $request, Product $product, Category $category)
    {
        $product->categories()->detach($category->id);
    }
}