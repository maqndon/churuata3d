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

    protected function getProductData(Request $request, $slug, $slug2, $relation)
    {
        try {
            $product = Product::with(['images', 'licence', 'tags', 'categories', 'print_settings', 'files'])
                ->where('slug', $slug2)
                ->where('status', 'published')
                ->whereRelation($relation, 'slug', $slug)
                ->firstOrFail();

            $commonContent = $this->productCommonContent->getProductCommonContent();
            $totalImages = collect($product->images->images_names)->count();
            $relatedParametric = $this->productService->getParametric($slug2);
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
                'relation'
            );
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    public function showProduct(Request $request, $slug, $slug2, $relation)
    {
        $data = $this->getProductData($request, $slug, $slug2, $relation);
        $data['slug'] = $slug;

        $slugType = $relation === 'categories' ? 'category_slug' : 'tag_slug';
        $data[$slugType] = $slug;

        return view('products.show', $data);
    }

    protected function getModelData(Request $request, $model, $slug, $relation)
    {
        $item = $model::where('slug', $slug)->firstOrFail();
        $products = Product::with($relation)
            ->whereRelation($relation, 'slug', $slug)
            ->get();

        $data = $this->loadCommonData();
        $slugType = $relation === 'categories' ? 'category_slug' : 'tag_slug';
        $slugName = $relation === 'categories' ? 'category_name' : 'tag_name';
        ${$slugType} = $slug;
        ${$slugName} = $item->name;

        return compact(
            'products',
            'item',
            $slugType,
            $slugName
        ) + $data;
    }

    public function showCommon(Request $request, $model, $slug, $relation)
    {
        $data = $this->getModelData($request, $model, $slug, $relation);
        return view($relation . '.show', $data);
    }
}
