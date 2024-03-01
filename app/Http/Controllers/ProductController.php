<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    public function show(Request $request, string $label, string $productSlug)
    {
        $category = Category::where('slug', $label)->first();
        $tag = Tag::where('slug', $label)->first();

        if ($category) {
            return $this->showProduct($request, $label, $productSlug, 'categories', Category::class);
        } elseif ($tag) {
            return $this->showProduct($request, $label, $productSlug, 'tags', Tag::class);
        } else {
            abort(404);
        }
    }

    public function index()
    {

        $data = $this->loadCommonData();
        $data['categories'] = Category::all();
        $data['mostDownloadedProductsCategory'] = $data['mostDownloadedProducts']->first()->categories->first()->slug;

        return view('products.index', $data);
    }

}
