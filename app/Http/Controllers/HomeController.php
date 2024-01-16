<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProductService;

class HomeController extends Controller
{

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function show()
    {
        $mostDownloadedProducts = $this->productService->getMostDownloaded(3);

        return view('welcome', compact('mostDownloadedProducts'));
    }
}
