<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

interface TagRepositoryInterface
{
    public function attachTag(Request $request, Product $product, Tag $tag);
    public function detachTag(Request $request, Product $product, Tag $tag);
}