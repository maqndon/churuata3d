<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function show(Request $request, string $label)
    {
        return $this->showCommon($request, Category::class, $label, 'categories');
    }
}
