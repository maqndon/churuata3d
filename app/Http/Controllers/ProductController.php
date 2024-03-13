<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    public function show(Request $request, string $labelSlug, string $productSlug)
    {
        $category = Category::where('slug', $labelSlug)->first();
        $tag = Tag::where('slug', $labelSlug)->first();
        
        if ($category) {
            return $this->showProduct($request, $labelSlug, $productSlug, 'categories', Category::class);
        } elseif ($tag) {
            return $this->showProduct($request, $labelSlug, $productSlug, 'tags', Tag::class);
        } else {
            abort(404);
        }
    }

    public function index()
    {

        $data = $this->loadCommonData();
        $this->loadCategoryData($data);

        return view('products.index', $data);
    }

}
