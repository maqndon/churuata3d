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

    protected function showCommon(Request $request, $model, $slug, $relation)
    {
        $item = $model::where('slug', $slug)->firstOrFail();
        $products = Product::with($relation)
            ->whereRelation($relation, 'slug', $slug)
            ->get();

        $data = $this->loadCommonData();

        $slugTypeData = $this->getRelationTypeAndSlug($relation, $slug, $products, $item);

        // data array
        $compactData = $slugTypeData + $data;

        // final view
        return view($this->getViewType($relation), $compactData);
    }

    protected function getViewType($relation)
    {
        // view type
        $viewType = $relation === 'categories' ? 'categories.show' : 'tags.show';

        return $viewType;
    }

    protected function getRelationTypeAndSlug($relation, $slug, $products, $item)
    {

        // slug type
        $slugType = $relation === 'categories' ? 'category_slug' : 'tag_slug';

        // slug value
        ${$slugType} = $slug;

        if ($slugType === 'category_slug') {
            return compact(
                'products',
                'item',
                'category_slug'
            );
        } elseif ($slugType === 'tag_slug') {
            return compact(
                'products',
                'item',
                'tag_slug'
            );
        }
    }

    public function showProduct(Request $request, $slug, $slug2, $relation)
    {

        try {
            //product
            $product = Product::with(['images', 'licence', 'tags', 'categories', 'print_settings', 'files'])
                ->where('slug', $slug2)
                ->where('status', 'published')
                ->whereRelation($relation, 'slug', $slug)
                ->first();

            //product common contents
            $commonContent =  $this->productCommonContent->getProductCommonContent();

            // Count the number of images associated with the product
            $totalImages = collect($product->images->images_names)->count();

            //related parametric product
            $relatedParametric = $this->productService->getParametric($slug2);

            //most downloaded products
            $data = $this->loadCommonData();
            // $mostDownloadedProducts = $this->mostDownloaded(3);

            //bill of materials
            $billOfMaterials = $this->getBillOfMaterials(Product::class, $product->id);

            //related products
            $relatedProducts = $this->productService->getRelatedProducts($product);

            switch ($relation) {
                case 'categories':
                    $category_slug = $slug;
                    return view('products.show', compact(
                        'category_slug',
                        'product',
                        'totalImages',
                        'commonContent',
                        'billOfMaterials',
                        'relatedProducts',
                        'relatedParametric',
                        'relation'
                    ) + $data);
                    break;
                case 'tags':
                    $tag_slug = $slug;
                    return view('products.show', compact(
                        'tag_slug',
                        'product',
                        'totalImages',
                        'commonContent',
                        'billOfMaterials',
                        'relatedProducts',
                        'relatedParametric',
                        'relation'
                    ) + $data);
                    break;
            }
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    protected function loadCommonData()
    {
        $mostDownloadedProducts = $this->mostDownloaded(3);

        return [
            'mostDownloadedProducts' => $mostDownloadedProducts,
        ];
    }

    protected function getRelation($relation, $slug)
    {
        switch ($relation) {
            case 'categories':
                return $category_slug = $slug;
                break;
            case 'tags':
                return $tag_slug = $slug;
                break;
        }
    }
}
