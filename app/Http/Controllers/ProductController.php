<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\BillOfMaterials;
use App\Services\ProductService;
use App\Services\ZipDownloadService;
use App\Services\ProductCommonContentService;

class ProductController extends Controller
{

    protected $zipDownloadService;
    protected $productService;
    protected $productCommonContent;

    use BillOfMaterials;
    
    public function __construct(ZipDownloadService $zipDownloadService, ProductService $productService, ProductCommonContentService $productCommonContent)
    {
        $this->zipDownloadService = $zipDownloadService;
        $this->productService = $productService;
        $this->productCommonContent = $productCommonContent;
    }

    public function show(Request $request, $slug)
    {

        try {
            //product
            $product = Product::with(['images', 'licence', 'tags', 'categories', 'print_settings', 'files'])
                ->where('slug', $slug)
                ->where('status', 'published')
                ->first();

            //product common contents
            $commonContent =  $this->productCommonContent->getProductCommonContent();

            // Count the number of images associated with the product
            $totalImages = collect($product->images->images_names)->count();

            //related parametric product
            $relatedParametric = $this->productService->getParametric($slug);

            //most downloaded products
            $mostDownloadedProducts = $this->productService->getMostDownloaded(3);

            //bill of materials
            $billOfMaterials = $this->getBillOfMaterials(Product::class, $product->id);
            
            //related products
            $relatedProducts = $this->productService->getRelatedProducts($product);

            return view('products.show', compact(
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

    public function downloadProductFiles($slug)
    {

        $fileNames = $this->productService->getFileNames($slug);

        // Set the zip name
        $zipFileName = 'files_' . $slug . '.zip';

        // Use the service to download files in a zip
        return $this->zipDownloadService->downloadFilesInZip($fileNames, $zipFileName, $slug, 'product');
    }
}
