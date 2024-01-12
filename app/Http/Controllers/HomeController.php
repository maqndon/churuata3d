<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function show()
    {
        //site settings
        $siteSettings = SiteSetting::first();

        //top downloaded products
        $products = Product::where('status', 'published')->get();

        $user = auth()->user()->name ?? 'guest';

        return view('welcome', compact(
            'user',
            'siteSettings',
            'products'
        ));
    }
}
