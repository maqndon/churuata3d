<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends BaseController
{

    public function show(Request $request, $slug, $slug2)
    {
        $category = Category::where('slug', $slug)->first();
        $tag = Tag::where('slug', $slug)->first();

        if ($category) {
            return $this->showProduct($request, $slug, $slug2, 'categories');
        } elseif ($tag) {
            return $this->showProduct($request, $slug, $slug2, 'tags');
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
