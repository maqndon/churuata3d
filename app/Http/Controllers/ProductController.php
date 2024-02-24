<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\BillOfMaterials;
use App\Services\ProductService;
use App\Services\ZipDownloadService;
use App\Services\ProductCommonContentService;
use App\Traits\DownloadFiles;
use App\Traits\MostDownloaded;

class ProductController extends Controller
{

    protected $zipDownloadService;
    protected $productService;
    protected $productCommonContent;

    use BillOfMaterials;
    use DownloadFiles;
    use MostDownloaded;

    public function __construct(ZipDownloadService $zipDownloadService, ProductService $productService, ProductCommonContentService $productCommonContent)
    {
        $this->zipDownloadService = $zipDownloadService;
        $this->productService = $productService;
        $this->productCommonContent = $productCommonContent;
    }

    public function show(Request $request, $category_slug, $product_slug)
    {

        try {
            //product
            $product = Product::with(['images', 'licence', 'tags', 'categories', 'print_settings', 'files'])
                ->where('slug', $product_slug)
                ->where('status', 'published')
                ->whereRelation('categories', 'slug', $category_slug)
                ->first();

            //product common contents
            $commonContent =  $this->productCommonContent->getProductCommonContent();

            // Count the number of images associated with the product
            $totalImages = collect($product->images->images_names)->count();

            //related parametric product
            $relatedParametric = $this->productService->getParametric($product_slug);

            //most downloaded products
            $mostDownloadedProducts = $this->mostDownloaded(3);

            //bill of materials
            $billOfMaterials = $this->getBillOfMaterials(Product::class, $product->id);

            //related products
            $relatedProducts = $this->productService->getRelatedProducts($product);

            return view('products.show', compact(
                'category_slug',
                'product',
                'mostDownloadedProducts',
                'totalImages',
                'commonContent',
                'billOfMaterials',
                'relatedProducts',
                'relatedParametric'
            ));
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function index()
    {
        $categories = Category::all();

        $mostDownloadedProducts = $this->mostDownloaded(3);
        $mostDownloadedProductsCategory = $mostDownloadedProducts->first()->categories->first()->slug;

        return view('products.index', compact(
            'categories',
            'mostDownloadedProducts',
            'mostDownloadedProductsCategory'
        ));
    }

    public function indexCategory(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstorFail();
        $products = Product::with('categories')
            ->whereRelation('categories', 'slug', $slug)
            ->get();

        $mostDownloadedProducts = $this->mostDownloaded(3);

        return view('products.indexCategory', compact(
            'products',
            'category',
            'slug',
            'mostDownloadedProducts',
        ));
    }
}
