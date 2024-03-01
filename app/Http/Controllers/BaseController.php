<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\DownloadFiles;
use App\Traits\MostDownloaded;
use App\Traits\BillOfMaterials;
use App\Services\ProductService;
use App\Services\ZipDownloadService;
use App\Services\ProductCommonContentService;

class BaseController extends Controller
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

    protected function loadCommonData()
    {
        $mostDownloadedProducts = $this->mostDownloaded(3);
        return [
            'mostDownloadedProducts' => $mostDownloadedProducts,
        ];
    }

    protected function getProductData(Request $request, string $label, string $productSlug, string $relation)
    {
        try {
            $product = Product::with(['images', 'licence', 'tags', 'categories', 'print_settings', 'files'])
                ->where('slug', $productSlug)
                ->where('status', 'published')
                ->whereRelation($relation, 'slug', $label)
                ->firstOrFail();

            $commonContent = $this->productCommonContent->getProductCommonContent();
            $totalImages = collect($product->images->images_names)->count();
            $relatedParametric = $this->productService->getParametric($productSlug);
            $data = $this->loadCommonData();
            $billOfMaterials = $this->getBillOfMaterials(Product::class, $product->id);
            $relatedProducts = $this->productService->getRelatedProducts($product);

            return compact(
                'product',
                'totalImages',
                'commonContent',
                'billOfMaterials',
                'relatedProducts',
                'relatedParametric',
            );
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    protected function getModelSlugData(Request $request, string $relation, string $label, string $model)
    {
        $item = $model::where('slug', $label)->firstOrFail();
        $slugType = $relation === 'categories' ? 'category_slug' : 'tag_slug';
        $slugName = $relation === 'categories' ? 'category_name' : 'tag_name';
        
        ${$slugType} = $label;
        ${$slugName} = $item->name;

        return compact(
            $slugType,
         $slugName,
         'item'
        );
    }

    protected function getModelProductsData(string $relation, string $label)
    {
        $products = Product::with($relation)
            ->whereRelation($relation, 'slug', $label)
            ->get();

        return compact('products');
    }

    public function showProduct(Request $request, string $label, string $productSlug, string  $relation, string $model)
    {
        $productData = $this->getProductData($request, $label, $productSlug, $relation);
        $labelData = $this->getModelSlugData($request, $relation, $label, $model);

        $data = array_merge($productData, $labelData);

        return view('products.show', $data);
    }

    public function showCommon(Request $request, string $model, string $label, string $relation)
    {
        $slugData = $this->getModelSlugData($request, $relation, $label, $model);
        $products = $this->getModelProductsData($relation, $label);

        $data = array_merge($slugData, $products);

        return view($relation . '.show', $data);
    }
}