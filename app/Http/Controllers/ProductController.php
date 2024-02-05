<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\BillOfMaterialService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\ProductCommonContentService;
use App\Services\ZipDownloadService;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{

    protected $zipDownloadService;
    protected $productService;
    protected $productCommonContent;
    protected $billOfMaterials;

    public function __construct(ZipDownloadService $zipDownloadService, ProductService $productService, ProductCommonContentService $productCommonContent, BillOfMaterialService $billOfMaterials)
    {
        $this->zipDownloadService = $zipDownloadService;
        $this->productService = $productService;
        $this->productCommonContent = $productCommonContent;
        $this->billOfMaterials = $billOfMaterials;
    }

    public function show(Request $request, $slug)
    {

        //product
        $product = Product::with(['images','licence', 'tags', 'categories', 'print_settings', 'files'])
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
        $billOfMaterials = $this->billOfMaterials->getBillOfMaterials('App\Models\Product', $product->id);

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
