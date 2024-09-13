<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function attachCategory(Request $request, Product $product, Category $category);
    public function detachCategory(Request $request, Product $product, Category $category);
}