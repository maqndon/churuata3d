<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagRepositoryEloquent implements TagRepositoryInterface
{
    public function attachTag(Request $request, Product $product, Tag $tag)
    {
        $product->tags()->syncWithoutDetaching([$tag->id]);
    }

    public function detachTag(Request $request, Product $product, Tag $tag)
    {
        $product->tags()->detach($tag->id);
    }
}